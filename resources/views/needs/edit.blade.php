<!-- resources/views/needs/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier le Besoin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Modifier le Besoin Étudiant</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('needs.update', $need) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Étudiant *</label>
                            <select class="form-control @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">Sélectionnez un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ (old('student_id', $need->student_id) == $student->id) ? 'selected' : '' }}>
                                        {{ $student->full_name }} - {{ $student->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priorité</label>
                            <select class="form-control @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority">
                                <option value="low" {{ (old('priority', $need->priority) == 'low') ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ (old('priority', $need->priority) == 'medium') ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ (old('priority', $need->priority) == 'high') ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $need->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $need->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="pending" {{ (old('status', $need->status) == 'pending') ? 'selected' : '' }}>En attente</option>
                            <option value="in_progress" {{ (old('status', $need->status) == 'in_progress') ? 'selected' : '' }}>En cours</option>
                            <option value="resolved" {{ (old('status', $need->status) == 'resolved') ? 'selected' : '' }}>Résolu</option>
                            <option value="cancelled" {{ (old('status', $need->status) == 'cancelled') ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('needs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#student_id').select2({
        placeholder: "Rechercher un étudiant...",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush