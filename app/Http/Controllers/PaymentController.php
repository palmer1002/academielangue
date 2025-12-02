<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Etudiant;
use App\Models\Registration;
use App\Mail\PaymentReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('registration.student')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Payment::count(),
            'payes' => Payment::where('statut', 'payé')->count(),
            'en_attente' => Payment::where('statut', 'en_attente')->count(),
            'en_retard' => Payment::where('statut', 'retard')->count(),
            'montant_total' => Payment::where('statut', 'payé')->sum('amount'),
            'montant_attente' => Payment::where('statut', 'en_attente')->sum('amount')
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function history(Request $request)
    {
        // Construire la requête
        $query = Payment::with('registration.student', 'registration.course');
        
        // Appliquer les filtres
        if ($request->has('student_id') && $request->student_id) {
            $query->whereHas('registration', function($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('payment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('payment_date', '<=', $request->date_to);
        }
        
        // Trier par date de paiement décroissante
        $query->orderBy('payment_date', 'desc');
        
        // Paginer les résultats
        $payments = $query->paginate(15)->appends($request->except('page'));
        
        // Calculer les statistiques
        $totalPayments = $payments->total();
        $totalAmount = $query->sum('amount');
        $averageAmount = $totalPayments > 0 ? $totalAmount / $totalPayments : 0;
        $thisMonthPayments = Payment::whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->count();
        
        return view('payments.history', compact('payments', 'totalPayments', 'totalAmount', 'averageAmount', 'thisMonthPayments'));
    }

    public function selectRegistration()
    {
        // Obtenir les étudiants avec des inscriptions actives qui ont des montants restants à payer
        $students = Etudiant::whereHas('registrations', function($query) {
                $query->where('status', 'active')
                      ->whereRaw('amount_paid < total_amount');
            })
            ->with(['registrations' => function($query) {
                $query->where('status', 'active')
                      ->whereRaw('amount_paid < total_amount');
            }])
            ->orderBy('last_name')
            ->get();

        return view('payments.select-registration', compact('students'));
    }

    public function create(Request $request)
    {
        $studentId = $request->get('student_id');
        
        // Si aucun student_id n'est fourni, nous devons afficher une liste de tous les étudiants avec des inscriptions impayées
        if (!$studentId) {
            // Obtenir tous les étudiants avec des inscriptions actives qui ont des montants restants à payer
            $students = Etudiant::whereHas('registrations', function($query) {
                    $query->where('status', 'active')
                          ->whereRaw('amount_paid < total_amount');
                })
                ->orderBy('last_name')
                ->get();
                
            // Obtenir les inscriptions pour le premier étudiant s'il y en a un
            $registrations = collect();
            $student = null;
            if ($students->isNotEmpty()) {
                $student = $students->first();
                $registrations = $student->registrations->filter(function($reg) {
                    return $reg->remaining_amount > 0;
                });
            }
            
            return view('payments.create', compact('students', 'student', 'registrations'));
        }

        // Si un student_id est fourni, obtenir cet étudiant spécifique avec ses inscriptions
        $student = Etudiant::with(['registrations' => function($query) {
                $query->where('status', 'active')
                      ->whereRaw('amount_paid < total_amount')
                      ->with('course');
            }])
            ->findOrFail($studentId);

        // Filtrer les inscriptions qui ont encore des montants restants
        $registrations = $student->registrations->filter(function($reg) {
            return $reg->remaining_amount > 0;
        });

        if ($registrations->isEmpty()) {
            return redirect()->route('payments.select-registration')
                ->with('error', 'Cet étudiant n\'a pas d\'inscriptions avec des soldes impayés.');
        }

        // Passer également tous les étudiants pour le menu déroulant
        $students = Etudiant::whereHas('registrations', function($query) {
                $query->where('status', 'active')
                      ->whereRaw('amount_paid < total_amount');
            })
            ->orderBy('last_name')
            ->get();

        return view('payments.create', compact('students', 'student', 'registrations'));
    }

    public function store(Request $request)
    {
        try {
            // Journaliser les données de la requête entrante pour le débogage
            \Illuminate\Support\Facades\Log::info('Payment store request data:', $request->all());
            
            $validatedData = $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_date' => 'required|date',
                'payment_method' => 'required|in:cash,check,transfer,card',
                'notes' => 'nullable|string',
                'marquer_paye' => 'nullable|boolean'
            ]);
            
            // Journaliser les données validées
            \Illuminate\Support\Facades\Log::info('Payment validated data:', $validatedData);

            $registration = Registration::findOrFail($validatedData['registration_id']);
            
            // Journaliser les informations d'inscription
            \Illuminate\Support\Facades\Log::info('Registration found:', ['id' => $registration->id, 'remaining_amount' => $registration->remaining_amount]);

            // Vérifier si le montant ne dépasse pas le montant restant
            if ($validatedData['amount'] > $registration->remaining_amount) {
                \Illuminate\Support\Facades\Log::warning('Payment amount exceeds remaining amount', [
                    'amount' => $validatedData['amount'],
                    'remaining' => $registration->remaining_amount
                ]);
                return back()->withInput()
                    ->with('error', 'Le montant ne peut pas dépasser le solde restant de ' . number_format($registration->remaining_amount, 2) . ' FCFA');
            }

            // Créer le paiement
            $payment = new Payment();
            $payment->registration_id = $validatedData['registration_id'];
            $payment->amount = $validatedData['amount'];
            $payment->payment_date = $validatedData['payment_date'];
            $payment->payment_method = $validatedData['payment_method'];
            $payment->statut = $validatedData['marquer_paye'] ? 'payé' : 'en_attente';
            $payment->receipt_number = Payment::generateReceiptNumber();
            $payment->notes = $validatedData['notes'] ?? null;
            $payment->save();
            
            // Journaliser la création du paiement
            \Illuminate\Support\Facades\Log::info('Payment created:', ['id' => $payment->id, 'receipt_number' => $payment->receipt_number]);

            // Mettre à jour le montant payé de l'inscription
            $registration->amount_paid += $validatedData['amount'];
            $registration->save();
            
            // Journaliser la mise à jour de l'inscription
            \Illuminate\Support\Facades\Log::info('Registration updated:', ['id' => $registration->id, 'amount_paid' => $registration->amount_paid]);

            // Envoyer le reçu par email
            try {
                if ($registration->student->email) {
                    Mail::to($registration->student->email)->send(new PaymentReceipt($payment));
                    \Illuminate\Support\Facades\Log::info('Payment receipt email sent', ['to' => $registration->student->email]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('No email address for student', ['student_id' => $registration->student->id]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send payment receipt email: ' . $e->getMessage());
            }

            return redirect()->route('payments.index')
                ->with('success', 'Paiement enregistré avec succès. Numéro de reçu: ' . $payment->receipt_number);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement du paiement: ' . $e->getMessage());
        }
    }

    public function receipt(Payment $payment)
    {
        $payment->load('registration.student', 'registration.course');
        return view('payments.receipt', compact('payment'));
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load('registration.student', 'registration.course');
        return view('payments.print', compact('payment'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $payment->load('registration.student', 'registration.course');
        
        // Générer le PDF à partir de la vue du reçu
        $pdf = Pdf::loadView('payments.receipt-pdf', compact('payment'));
        
        // Créer le nom du fichier
        $filename = 'receipt_' . $payment->receipt_number . '.pdf';
        
        // Retourner la réponse de téléchargement du PDF
        return $pdf->download($filename);
    }

    public function destroy(Payment $payment)
    {
        // Stocker le numéro de reçu pour le message
        $receiptNumber = $payment->receipt_number;
        
        // Rembourser le montant de l'inscription
        $registration = $payment->registration;
        $registration->amount_paid -= $payment->amount;
        $registration->save();
        
        // Supprimer le paiement
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Paiement #' . $receiptNumber . ' supprimé avec succès.');
    }

    public function marquerPaye(Payment $payment)
    {
        $payment->statut = 'payé';
        $payment->save();

        return redirect()->route('payments.index')
            ->with('success', 'Paiement marqué comme payé.');
    }

    // Méthodes API
    public function getStudentRegistrations($studentId)
    {
        $registrations = Registration::with('course')
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->whereRaw('amount_paid < total_amount')
            ->get()
            ->map(function($reg) {
                return [
                    'id' => $reg->id,
                    'course_name' => $reg->course->name ?? 'N/A',
                    'course_level' => $reg->course->level ?? 'N/A',
                    'remaining_amount' => $reg->remaining_amount,
                ];
            });

        return response()->json($registrations);
    }

    public function getRegistrationDetails($registrationId)
    {
        $registration = Registration::with('course', 'student')
            ->findOrFail($registrationId);

        return response()->json([
            'id' => $registration->id,
            'course_name' => $registration->course->name ?? 'N/A',
            'course_level' => $registration->course->level ?? 'N/A',
            'student_name' => $registration->student->full_name ?? 'N/A',
            'remaining_amount' => $registration->remaining_amount,
        ]);
    }

    public function getStudentInfo(Etudiant $etudiant)
    {
        $studentData = [
            'id' => $etudiant->id,
            'full_name' => $etudiant->full_name,
            'email' => $etudiant->email,
            'phone' => $etudiant->phone,
            'registrations_count' => $etudiant->registrations()->where('status', '!=', 'completed')->count(),
        ];

        return response()->json($studentData);
    }
    
    public function getAllStudents()
    {
        $students = Etudiant::select('id', 'first_name', 'last_name', 'email')
            ->orderBy('last_name')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'email' => $student->email
                ];
            });
            
        return response()->json($students);
    }
}