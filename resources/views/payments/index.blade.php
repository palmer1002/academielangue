@extends('layouts.app')

@section('title', 'Historique des Paiements')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-history me-2"></i>Historique des Paiements</h5>
                <div class="btn-group">
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau Paiement
                    </a>
                    <a href="{{ route('payments.history') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar me-2"></i>Historique Détaillé
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistiques -->
                @if(isset($stats))
                <div class="row mb-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <h6>Total</h6>
                                <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <h6>Payés</h6>
                                <h4 class="mb-0">{{ $stats['payes'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <h6>En Attente</h6>
                                <h4 class="mb-0">{{ $stats['en_attente'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body text-center">
                                <h6>En Retard</h6>
                                <h4 class="mb-0">{{ $stats['en_retard'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <h6>Montant Total Payé</h6>
                                <h4 class="mb-0">{{ number_format($stats['montant_total'] ?? 0, 2, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Messages de session -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($payments->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun paiement enregistré</h5>
                        <p class="text-muted">Commencez par enregistrer un nouveau paiement.</p>
                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Enregistrer un Paiement
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Reçu</th>
                                    <th>Étudiant</th>
                                    <th>Cours</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Méthode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->receipt_number }}</td>
                                    <td>{{ $payment->registration->student->full_name }}</td>
                                    <td>{{ $payment->registration->course->name }} ({{ $payment->registration->course->level }})</td>
                                    <td>{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ $payment->payment_method_text }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->statut == 'payé' ? 'success' : ($payment->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-info" title="Voir le reçu">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            <a href="{{ route('payments.download', $payment) }}" class="btn btn-sm btn-outline-secondary" title="Télécharger le reçu">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if($payment->statut != 'payé')
                                            <form action="{{ route('payments.marquer-paye', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Marquer comme payé" 
                                                        onclick="return confirm('Marquer ce paiement comme payé?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection