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
     * Display payments by level report.
     */
    public function paymentsByLevel()
    {
        // Get payments grouped by course level
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

        // Get total payments across all levels
        $totalPayments = Payment::sum('amount');

        return view('reports.payments-by-level', compact('paymentsByLevel', 'totalPayments'));
    }

    /**
     * Display student balances report.
     */
    public function studentBalances()
    {
        // Get students with their registration balances
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