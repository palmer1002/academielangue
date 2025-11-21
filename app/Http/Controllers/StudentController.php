<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Etudiant::orderBy('last_name')->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        Etudiant::create($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Etudiant $student)
    {
        $student->load('registrations.course');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etudiant $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etudiant $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etudiant $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    /**
     * Get registrations for a specific student.
     */
    public function registrations(Etudiant $etudiant)
    {
        $registrations = $etudiant->registrations()
            ->with('course')
            ->where('status', '!=', 'completed')
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'course_name' => $registration->course->name,
                    'course_level' => $registration->course->level,
                    'remaining_amount' => $registration->remaining_amount,
                ];
            });

        return response()->json($registrations);
    }

    /**
     * Get student information for AJAX requests.
     */
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
}