<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Etudiant;
use App\Models\Course;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Afficher une liste des ressources.
     */
    public function index()
    {
        $registrations = Registration::with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('registrations.index', compact('registrations'));
    }

    /**
     * Afficher le formulaire pour créer une nouvelle ressource.
     */
    public function create()
    {
        $students = Etudiant::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        return view('registrations.create', compact('students', 'courses'));
    }

    /**
     * Stocker une ressource nouvellement créée dans le stockage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'course_id' => 'required|exists:courses,id',
            'registration_date' => 'required|date',
            'status' => 'required|string|in:pending,active,completed,cancelled',
        ]);

        // Obtenir le cours pour définir le montant total
        $course = Course::findOrFail($request->course_id);
        
        // Calculer les dates de début et de fin
        $startDate = $request->registration_date;
        $endDate = date('Y-m-d', strtotime("+$course->duration_days days", strtotime($startDate)));
        
        // Créer l'inscription avec les valeurs appropriées
        Registration::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'registration_date' => $request->registration_date,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $course->price,
            'amount_paid' => 0,
            'status' => $request->status
        ]);

        return redirect()->route('registrations.index')
            ->with('success', 'Inscription créée avec succès.');
    }

    /**
     * Afficher la ressource spécifiée.
     */
    public function show(Registration $registration)
    {
        $registration->load(['student', 'course', 'payments']);
        return view('registrations.show', compact('registration'));
    }

    /**
     * Afficher le formulaire pour modifier la ressource spécifiée.
     */
    public function edit(Registration $registration)
    {
        $students = Etudiant::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        return view('registrations.edit', compact('registration', 'students', 'courses'));
    }

    /**
     * Mettre à jour la ressource spécifiée dans le stockage.
     */
    public function update(Request $request, Registration $registration)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'course_id' => 'required|exists:courses,id',
            'registration_date' => 'required|date',
            'status' => 'required|string|in:pending,active,completed,cancelled',
        ]);

        // Obtenir le cours pour définir le montant total
        $course = Course::findOrFail($request->course_id);
        
        // Calculer les dates de début et de fin
        $startDate = $request->registration_date;
        $endDate = date('Y-m-d', strtotime("+$course->duration_days days", strtotime($startDate)));
        
        // Mettre à jour l'inscription avec les valeurs appropriées
        $registration->update([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'registration_date' => $request->registration_date,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $course->price,
            'status' => $request->status
        ]);

        return redirect()->route('registrations.index')
            ->with('success', 'Inscription mise à jour avec succès.');
    }

    /**
     * Supprimer la ressource spécifiée du stockage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('registrations.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }
}