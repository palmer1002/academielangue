@extends('layouts.app')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Enregistrer un Nouveau Paiement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    
                    <!-- Étudiant -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Étudiant *</label>
                            <select class="form-control @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">Sélectionnez un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ (isset($student) && $student->id == $student->id) || old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informations de l'étudiant -->
                    <div id="student-info-section" style="display: none;">
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6>Informations de l'Étudiant</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nom complet:</strong> <span id="student-full-name"></span></p>
                                        <p><strong>Email:</strong> <span id="student-email"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Téléphone:</strong> <span id="student-phone"></span></p>
                                        <p><strong>Inscriptions actives:</strong> <span id="student-registrations-count"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inscription -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="registration_id" class="form-label">Inscription *</label>
                            <select class="form-control @error('registration_id') is-invalid @enderror" 
                                    id="registration_id" name="registration_id" required>
                                <option value="">Sélectionnez une inscription</option>
                                @if(isset($registrations))
                                    @foreach($registrations as $reg)
                                        <option value="{{ $reg->id }}" 
                                                data-amount="{{ $reg->remaining_amount }}"
                                                {{ old('registration_id') == $reg->id ? 'selected' : '' }}>
                                            {{ $reg->course->name }} ({{ $reg->course->level }}) - 
                                            Reste: {{ number_format($reg->remaining_amount, 2, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('registration_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Détails du paiement -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="amount" class="form-label">Montant (FCFA) *</label>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" 
                                   step="0.01" min="0.01" 
                                   value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="remaining-amount-text"></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="payment_date" class="form-label">Date de Paiement *</label>
                            <input type="date" 
                                   class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" 
                                   value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="payment_method" class="form-label">Méthode de paiement *</label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">Sélectionnez une méthode</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="notes" class="form-label">Commentaires (optionnel)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" 
                                      rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Marquer comme payé -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="marquer_paye" name="marquer_paye" value="1" {{ old('marquer_paye') ? 'checked' : '' }}>
                                <label class="form-check-label" for="marquer_paye">
                                    Marquer ce paiement comme payé immédiatement
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer le Paiement
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
    const studentSelect = document.getElementById('student_id');
    const registrationSelect = document.getElementById('registration_id');
    const amountInput = document.getElementById('amount');
    const remainingAmountText = document.getElementById('remaining-amount-text');
    
    // Show registration section when student is selected
    studentSelect.addEventListener('change', function() {
        if (this.value) {
            // Make an AJAX call to get student information
            fetch(`/api/student-info/${this.value}`)
                .then(response => response.json())
                .then(studentData => {
                    // Populate student information
                    document.getElementById('student-full-name').textContent = studentData.full_name;
                    document.getElementById('student-email').textContent = studentData.email || 'Non spécifié';
                    document.getElementById('student-phone').textContent = studentData.phone || 'Non spécifié';
                    document.getElementById('student-registrations-count').textContent = studentData.registrations_count;
                    
                    // Show the student info section
                    document.getElementById('student-info-section').style.display = 'block';
                    
                    // Make another AJAX call to get registrations for this student
                    return fetch(`/api/student-registrations/${this.value}`);
                })
                .then(response => response.json())
                .then(data => {
                    // Clear existing options
                    registrationSelect.innerHTML = '<option value="">Sélectionnez une inscription</option>';
                    
                    // Add new options
                    data.forEach(reg => {
                        const option = document.createElement('option');
                        option.value = reg.id;
                        option.dataset.amount = reg.remaining_amount;
                        option.dataset.courseName = reg.course_name;
                        option.dataset.courseLevel = reg.course_level;
                        option.textContent = `${reg.course_name} (${reg.course_level}) - Reste: ${parseFloat(reg.remaining_amount).toFixed(2)} FCFA`;
                        registrationSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Erreur lors du chargement des données');
                });
        } else {
            // Hide student info section
            document.getElementById('student-info-section').style.display = 'none';
            // Clear registration options
            registrationSelect.innerHTML = '<option value="">Sélectionnez une inscription</option>';
            remainingAmountText.textContent = '';
        }
    });
    
    // Show payment details when registration is selected
    registrationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const amount = parseFloat(selectedOption.dataset.amount);
            document.getElementById('amount').value = amount.toFixed(2);
            remainingAmountText.textContent = 'Solde restant: ' + amount.toFixed(2) + ' FCFA';
            document.getElementById('amount').max = amount.toFixed(2);
        } else {
            remainingAmountText.textContent = '';
        }
    });
});
</script>
@endpush