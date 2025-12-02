<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class StudentNeedController extends Controller
{
    /**
     * Afficher une liste des ressources.
     */
    public function index()
    {
        $needs = Need::with('student')->orderBy('created_at', 'desc')->paginate(10);
        return view('needs.index', compact('needs'));
    }

    /**
     * Afficher le formulaire pour créer une nouvelle ressource.
     */
    public function create()
    {
        $students = Etudiant::orderBy('last_name')->get();
        return view('needs.create', compact('students'));
    }

    /**
     * Stocker une ressource nouvellement créée dans le stockage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $data = $request->all();
        $data['status'] = $data['status'] ?? 'pending';
        
        Need::create($data);

        return redirect()->route('needs.index')
            ->with('success', 'Besoins créés avec succès.');
    }

    /**
     * Afficher la ressource spécifiée.
     */
    public function show(Need $need)
    {
        $need->load('student');
        return view('needs.show', compact('need'));
    }

    /**
     * Afficher le formulaire pour modifier la ressource spécifiée.
     */
    public function edit(Need $need)
    {
        $students = Etudiant::orderBy('last_name')->get();
        return view('needs.edit', compact('need', 'students'));
    }

    /**
     * Mettre à jour la ressource spécifiée dans le stockage.
     */
    public function update(Request $request, Need $need)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
        ]);

        $need->update($request->all());

        return redirect()->route('needs.index')
            ->with('success', 'Besoins mis à jour avec succès.');
    }

    /**
     * Mettre à jour le statut de la ressource spécifiée.
     */
    public function updateStatus(Request $request, Need $need)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
        ]);

        $need->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Supprimer la ressource spécifiée du stockage.
     */
    public function destroy(Need $need)
    {
        $need->delete();

        return redirect()->route('needs.index')
            ->with('success', 'Besoins supprimés avec succès.');
    }
}