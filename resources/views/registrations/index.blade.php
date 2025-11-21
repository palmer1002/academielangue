<!-- resources/views/registrations/index.blade.php -->
@extends('layouts.app')

@section('title', 'Inscriptions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Liste des Inscriptions</h5>
                <a href="{{ route('registrations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle Inscription
                </a>
            </div>
            <div class="card-body">
                @if($registrations->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune inscription enregistrée</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Cours</th>
                                    <th>Niveau</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->student->full_name }}</td>
                                    <td>{{ $registration->course->name }}</td>
                                    <td>{{ $registration->course->level }}</td>
                                    <td>{{ $registration->registration_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $registration->status == 'active' ? 'success' : ($registration->status == 'pending' ? 'warning' : ($registration->status == 'completed' ? 'info' : 'secondary')) }}">
                                            {{ $registration->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('registrations.show', $registration) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                        <a href="{{ route('registrations.edit', $registration) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>Modifier
                                        </a>
                                        <form action="{{ route('registrations.destroy', $registration) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription?')">
                                                <i class="fas fa-trash me-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection