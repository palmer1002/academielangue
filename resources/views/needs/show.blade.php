<!-- resources/views/needs/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails du Besoin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Détails du Besoin Étudiant</h5>
                <div>
                    <a href="{{ route('needs.edit', $need) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('needs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations du Besoin</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Étudiant:</strong></td>
                                <td>{{ $need->student->full_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Titre:</strong></td>
                                <td>{{ $need->title }}</td>
                            </tr>
                            <tr>
                                <td><strong>Priorité:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $need->priority == 'high' ? 'danger' : ($need->priority == 'medium' ? 'warning' : 'secondary') }}">
                                        {{ $need->priority }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td>{{ $need->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $need->status == 'completed' ? 'success' : ($need->status == 'in_progress' ? 'primary' : 'secondary') }}">
                                        {{ $need->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <p>{{ $need->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection