<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Etudiant;
use App\Models\Course;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = Registration::with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('registrations.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Etudiant::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        return view('registrations.create', compact('students', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'course_id' => 'required|exists:courses,id',
            'registration_date' => 'required|date',
            'status' => 'required|string|in:pending,active,completed,cancelled',
        ]);

        // Get the course to set the total amount
        $course = Course::findOrFail($request->course_id);
        
        // Calculate start and end dates
        $startDate = $request->registration_date;
        $endDate = date('Y-m-d', strtotime("+$course->duration_days days", strtotime($startDate)));
        
        // Create registration with proper values
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
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        $registration->load(['student', 'course', 'payments']);
        return view('registrations.show', compact('registration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        $students = Etudiant::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        return view('registrations.edit', compact('registration', 'students', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'course_id' => 'required|exists:courses,id',
            'registration_date' => 'required|date',
            'status' => 'required|string|in:pending,active,completed,cancelled',
        ]);

        // Get the course to set the total amount
        $course = Course::findOrFail($request->course_id);
        
        // Calculate start and end dates
        $startDate = $request->registration_date;
        $endDate = date('Y-m-d', strtotime("+$course->duration_days days", strtotime($startDate)));
        
        // Update registration with proper values
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
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('registrations.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }
}