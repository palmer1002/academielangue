<!-- resources/views/reports/student-balances.blade.php -->
@extends('layouts.app')

@section('title', 'Rapport - Soldes des Étudiants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-file-invoice-dollar me-2"></i>Soldes des Étudiants</h5>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($students->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun solde dû par les étudiants</p>
                    </div>
                @else
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Nombre d'Étudiants avec Solde</h6>
                                    <h4>{{ $students->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Montant Total Dû</h6>
                                    <h4>{{ number_format($students->sum('balance'), 2) }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Montant Total Payé</h6>
                                    <h4>{{ number_format($students->sum('total_paid'), 2) }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Report Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Total Dû (FCFA)</th>
                                    <th>Total Payé (FCFA)</th>
                                    <th>Solde Dû (FCFA)</th>
                                    <th>% Payé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ number_format($student->total_due, 2) }}</td>
                                    <td>{{ number_format($student->total_paid, 2) }}</td>
                                    <td class="text-danger"><strong>{{ number_format($student->balance, 2) }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $student->total_due > 0 ? ($student->total_paid / $student->total_due) * 100 : 0 }}%" 
                                                     aria-valuenow="{{ $student->total_due > 0 ? ($student->total_paid / $student->total_due) * 100 }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span>{{ $student->total_due > 0 ? number_format(($student->total_paid / $student->total_due) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th>{{ number_format($students->sum('total_due'), 2) }} FCFA</th>
                                    <th>{{ number_format($students->sum('total_paid'), 2) }} FCFA</th>
                                    <th class="text-danger">{{ number_format($students->sum('balance'), 2) }} FCFA</th>
                                    <th>{{ $students->sum('total_due') > 0 ? number_format(($students->sum('total_paid') / $students->sum('total_due')) * 100, 1) : 0 }}%</th>
                                </tr>
                            </tfoot>
                        </table>
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