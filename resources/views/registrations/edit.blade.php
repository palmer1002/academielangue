<!-- resources/views/registrations/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier Inscription')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Modifier Inscription</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('registrations.update', $registration) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Étudiant *</label>
                            <select class="form-control @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">Sélectionnez un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $registration->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} - {{ $student->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="course_id" class="form-label">Cours *</label>
                            <select class="form-control @error('course_id') is-invalid @enderror" 
                                    id="course_id" name="course_id" required>
                                <option value="">Sélectionnez un cours</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $registration->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->level }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registration_date" class="form-label">Date d'inscription *</label>
                            <input type="date" class="form-control @error('registration_date') is-invalid @enderror" 
                                   id="registration_date" name="registration_date" 
                                   value="{{ old('registration_date', $registration->registration_date->format('Y-m-d')) }}" required>
                            @error('registration_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Statut *</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ (old('status', $registration->status) == 'pending') ? 'selected' : '' }}>En attente</option>
                                <option value="active" {{ (old('status', $registration->status) == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ (old('status', $registration->status) == 'completed') ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled" {{ (old('status', $registration->status) == 'cancelled') ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $registration->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('registrations.index') }}" class="btn btn-secondary">
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
    
    $('#course_id').select2({
        placeholder: "Rechercher un cours...",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush