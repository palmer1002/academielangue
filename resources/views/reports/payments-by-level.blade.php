<!-- resources/views/reports/payments-by-level.blade.php -->
@extends('layouts.app')

@section('title', 'Rapport - Paiements par Niveau')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-chart-bar me-2"></i>Paiements par Niveau de Cours</h5>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($paymentsByLevel->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun paiement enregistré</p>
                    </div>
                @else
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Nombre Total de Paiements</h6>
                                    <h4>{{ $paymentsByLevel->sum('payment_count') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Montant Total Collecté</h6>
                                    <h4>{{ number_format($totalPayments, 2) }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Nombre de Niveaux</h6>
                                    <h4>{{ $paymentsByLevel->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Report Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Niveau de Cours</th>
                                    <th>Nombre de Paiements</th>
                                    <th>Montant Total (FCFA)</th>
                                    <th>% du Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentsByLevel as $level)
                                <tr>
                                    <td><strong>{{ $level->level }}</strong></td>
                                    <td>{{ $level->payment_count }}</td>
                                    <td>{{ number_format($level->total_amount, 2) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
@php
                                                        $progressPercentage = $totalPayments > 0 ? ($level->total_amount / $totalPayments) * 100 : 0;
                                                    @endphp
                                                    style="width: {{ $progressPercentage }}%" 
                                                     aria-valuenow="{{ $progressPercentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
@php
                                                $spanPercentage = $totalPayments > 0 ? ($level->total_amount / $totalPayments) * 100 : 0;
                                            @endphp
                                            <span>{{ number_format($spanPercentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $paymentsByLevel->sum('payment_count') }}</th>
                                    <th>{{ number_format($totalPayments, 2) }} FCFA</th>
                                    <th>100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Chart Visualization -->
                    <div class="mt-5">
                        <h6><i class="fas fa-chart-pie me-2"></i>Répartition des Paiements par Niveau</h6>
                        <div class="row">
                            @foreach($paymentsByLevel as $level)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $level->level }}</h6>
                                        <div class="display-6 text-primary mb-2">{{ number_format($level->total_amount, 0) }} FCFA</div>
                                        <div class="small text-muted">{{ $level->payment_count }} paiements</div>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">
@php
                                                    $cardPercentage = $totalPayments > 0 ? ($level->total_amount / $totalPayments) * 100 : 0;
                                                @endphp
                                                {{ number_format($cardPercentage, 1) }}% du total
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    .table {
        font-size: 12px;
    }
}
</style>
@endsection