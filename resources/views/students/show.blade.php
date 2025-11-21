<!-- resources/views/students/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails Étudiant')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Détails de l'Étudiant</h5>
                <div>
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations Personnelles</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Prénom:</strong></td>
                                <td>{{ $student->first_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nom:</strong></td>
                                <td>{{ $student->last_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $student->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone:</strong></td>
                                <td>{{ $student->phone ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date de Naissance:</strong></td>
                                <td>{{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'Non spécifié' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Adresse</h6>
                        <p>{{ $student->address ?? 'Non spécifiée' }}</p>
                        
                        <h6 class="mt-4">Statistiques</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Inscriptions:</strong></td>
                                <td>{{ $student->registrations->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h6>Inscriptions</h6>
                @if($student->registrations->isEmpty())
                    <p class="text-muted">Aucune inscription pour cet étudiant.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Niveau</th>
                                    <th>Date d'Inscription</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->registrations as $registration)
                                <tr>
                                    <td>{{ $registration->course->name }}</td>
                                    <td>{{ $registration->course->level }}</td>
                                    <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $registration->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $registration->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection