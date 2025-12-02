<!-- resources/views/needs/index.blade.php -->
@extends('layouts.app')

@section('title', 'Besoins des Étudiants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Liste des Besoins des Étudiants</h5>
                <a href="{{ route('needs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouveau Besoin
                </a>
            </div>
            <div class="card-body">
                @if($needs->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun besoin enregistré</p>
                        <a href="{{ route('needs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer le Premier Besoin
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Priorité</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($needs as $need)
                                <tr>
                                    <td>{{ $need->student->full_name }}</td>
                                    <td>{{ $need->title }}</td>
                                    <td>{{ Str::limit($need->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $need->priority == 'high' ? 'danger' : ($need->priority == 'medium' ? 'warning' : 'secondary') }}">
                                            {{ $need->priority_text }}
                                        </span>
                                    </td>
                                    <td>{{ $need->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $need->status == 'completed' ? 'success' : ($need->status == 'in_progress' ? 'primary' : 'secondary') }}">
                                            {{ $need->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('needs.show', $need) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                        <a href="{{ route('needs.edit', $need) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>Modifier
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $needs->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection