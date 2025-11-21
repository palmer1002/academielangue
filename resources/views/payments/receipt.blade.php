@extends('layouts.app')

@section('title', 'Reçu de Paiement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Reçu de Paiement</h5>
                    <div class="btn-group">
                        <button onclick="window.print()" class="btn btn-outline-primary">
                            <i class="fas fa-print me-1"></i>Imprimer
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="receipt-container">
                        <!-- En-tête du reçu -->
                        <div class="text-center mb-4">
                            <h2 class="mb-1">ACADEMIE DE LANGUES</h2>
                            <p class="text-muted">Centre de formation linguistique</p>
                            <hr>
                        </div>
                        
                        <!-- Informations du reçu -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="fw-bold me-2">N° Reçu:</label>
                                    <span>{{ $payment->receipt_number }}</span>
                                </div>
                                <div class="mb-2">
                                    <label class="fw-bold me-2">Date:</label>
                                    <span>{{ $payment->payment_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="mb-2">
                                    <label class="fw-bold me-2">Méthode:</label>
                                    <span>{{ $payment->payment_method_text }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="fw-bold me-2">Étudiant:</label>
                                    <span>{{ $payment->registration->student->full_name }}</span>
                                </div>
                                <div class="mb-2">
                                    <label class="fw-bold me-2">Email:</label>
                                    <span>{{ $payment->registration->student->email ?? 'Non spécifié' }}</span>
                                </div>
                                <div class="mb-2">
                                    <label class="fw-bold me-2">Téléphone:</label>
                                    <span>{{ $payment->registration->student->phone ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Détails du paiement -->
                        <div class="payment-details bg-light p-3 rounded mb-4">
                            <h5 class="border-bottom pb-2 mb-3">Détails du Paiement</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label class="fw-bold me-2">Cours:</label>
                                        <span>{{ $payment->registration->course->name }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <label class="fw-bold me-2">Niveau:</label>
                                        <span>{{ $payment->registration->course->level }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label class="fw-bold me-2">Date d'Inscription:</label>
                                        <span>{{ $payment->registration->registration_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <label class="fw-bold me-2">Montant Payé:</label>
                                        <span class="fs-5 fw-bold text-success">{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        @if($payment->notes)
                        <div class="notes-section mb-4">
                            <h5 class="border-bottom pb-2 mb-3">Commentaires</h5>
                            <p>{{ $payment->notes }}</p>
                        </div>
                        @endif
                        
                        <!-- Signature -->
                        <div class="text-end mt-5 pt-3 border-top">
                            <p class="mb-0">Signature du caissier</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.receipt-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.receipt-container h2 {
    color: #4e73df;
}

.payment-details {
    background-color: #f8f9fc;
}
</style>
@endpush