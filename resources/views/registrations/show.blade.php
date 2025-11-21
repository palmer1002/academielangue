<!-- resources/views/registrations/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails Inscription')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Détails de l'Inscription</h5>
                <div>
                    <a href="{{ route('registrations.edit', $registration) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('registrations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations de l'Inscription</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Étudiant:</strong></td>
                                <td>{{ $registration->student->full_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cours:</strong></td>
                                <td>{{ $registration->course->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Niveau:</strong></td>
                                <td>{{ $registration->course->level }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date d'Inscription:</strong></td>
                                <td>{{ $registration->registration_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $registration->status == 'active' ? 'success' : ($registration->status == 'pending' ? 'warning' : ($registration->status == 'completed' ? 'info' : 'secondary')) }}">
                                        {{ $registration->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails du Cours</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Prix:</strong></td>
                                <td>{{ number_format($registration->course->price, 2) }} FCFA</td>
                            </tr>
                            <tr>
                                <td><strong>Durée:</strong></td>
                                <td>{{ $registration->course->duration_days }} jours</td>
                            </tr>
                        </table>
                        
                        <h6 class="mt-4">Statistiques</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Paiements:</strong></td>
                                <td>{{ $registration->payments->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Montant Total Payé:</strong></td>
                                <td>{{ number_format($registration->payments->sum('amount'), 2) }} FCFA</td>
                            </tr>
                            <tr>
                                <td><strong>Solde Restant:</strong></td>
                                <td>{{ number_format($registration->course->price - $registration->payments->sum('amount'), 2) }} FCFA</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Paiements</h6>
                    <a href="{{ route('payments.create', ['registration' => $registration->id]) }}" 
                       class="btn btn-lg btn-success shadow-lg" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="top" 
                       title="Ajouter un nouveau paiement pour cette inscription">
                        <i class="fas fa-plus-circle me-2 animate__animated animate__pulse animate__infinite"></i>Ajouter un Paiement
                    </a>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card bg-light border-success">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Payé</h6>
                                <h4 class="text-success">{{ number_format($registration->payments->sum('amount'), 2) }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-danger">
                            <div class="card-body text-center">
                                <h6 class="card-title">Solde Restant</h6>
                                <h4 class="text-danger">{{ number_format($registration->course->price - $registration->payments->sum('amount'), 2) }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-primary">
                            <div class="card-body text-center">
                                <h6 class="card-title">Paiements</h6>
                                <h4>{{ $registration->payments->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($registration->payments->isEmpty())
                    <div class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun paiement enregistré pour cette inscription.
                        </div>
                        <a href="{{ route('payments.create', ['registration' => $registration->id]) }}" 
                           class="btn btn-lg btn-success shadow-lg animate__animated animate__pulse animate__infinite" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="top" 
                           title="Créer le premier paiement pour cette inscription">
                            <i class="fas fa-plus-circle me-2"></i>Créer le Premier Paiement
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registration->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} FCFA</td>
                                    <td>{{ $payment->payment_method_text }}</td>
                                    <td>
                                        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-receipt me-1"></i>Reçu
                                        </a>
                                        <a href="{{ route('payments.download', $payment) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Télécharger
                                        </a>
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