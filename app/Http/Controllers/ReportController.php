<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Etudiant;
use App\Models\Registration;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Afficher le rapport des paiements par niveau.
     */
    public function paymentsByLevel()
    {
        // Obtenir les paiements regroupés par niveau de cours
        $paymentsByLevel = Payment::select(
                'courses.level',
                DB::raw('COUNT(payments.id) as payment_count'),
                DB::raw('SUM(payments.amount) as total_amount')
            )
            ->join('registrations', 'payments.registration_id', '=', 'registrations.id')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->groupBy('courses.level')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Obtenir le total des paiements pour tous les niveaux
        $totalPayments = Payment::sum('amount');

        return view('reports.payments-by-level', compact('paymentsByLevel', 'totalPayments'));
    }

    /**
     * Afficher le rapport des soldes des étudiants.
     */
    public function studentBalances()
    {
        // Obtenir les étudiants avec leurs soldes d'inscription
        $students = Etudiant::select(
                'etudiants.id',
                'etudiants.first_name',
                'etudiants.last_name',
                DB::raw('SUM(courses.price) as total_due'),
                DB::raw('SUM(COALESCE(payments.amount, 0)) as total_paid'),
                DB::raw('SUM(courses.price) - SUM(COALESCE(payments.amount, 0)) as balance')
            )
            ->join('registrations', 'etudiants.id', '=', 'registrations.student_id')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->leftJoin('payments', 'registrations.id', '=', 'payments.registration_id')
            ->groupBy('etudiants.id', 'etudiants.first_name', 'etudiants.last_name')
            ->having('balance', '>', 0)
            ->orderBy('balance', 'desc')
            ->get();

        return view('reports.student-balances', compact('students'));
    }
}