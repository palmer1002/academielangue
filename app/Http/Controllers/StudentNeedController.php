<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class StudentNeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $needs = Need::with('student')->orderBy('created_at', 'desc')->paginate(10);
        return view('needs.index', compact('needs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Etudiant::orderBy('last_name')->get();
        return view('needs.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Need $need)
    {
        $need->load('student');
        return view('needs.show', compact('need'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Need $need)
    {
        $students = Etudiant::orderBy('last_name')->get();
        return view('needs.edit', compact('need', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Need $need)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,in_progress,resolved,cancelled',
        ]);

        $need->update($request->all());

        return redirect()->route('needs.index')
            ->with('success', 'Besoins mis à jour avec succès.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Need $need)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,resolved,cancelled',
        ]);

        $need->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Need $need)
    {
        $need->delete();

        return redirect()->route('needs.index')
            ->with('success', 'Besoins supprimés avec succès.');
    }
}