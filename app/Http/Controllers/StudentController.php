<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Afficher une liste des ressources.
     */
    public function index()
    {
        $students = Etudiant::orderBy('last_name')->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Afficher le formulaire pour créer une nouvelle ressource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Stocker une ressource nouvellement créée dans le stockage.
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
     * Afficher la ressource spécifiée.
     */
    public function show(Etudiant $student)
    {
        $student->load('registrations.course');
        return view('students.show', compact('student'));
    }

    /**
     * Afficher le formulaire pour modifier la ressource spécifiée.
     */
    public function edit(Etudiant $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Mettre à jour la ressource spécifiée dans le stockage.
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
     * Supprimer la ressource spécifiée du stockage.
     */
    public function destroy(Etudiant $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    /**
     * Obtenir les inscriptions pour un étudiant spécifique.
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
     * Obtenir les informations de l'étudiant pour les requêtes AJAX.
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