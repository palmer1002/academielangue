@extends('layouts.app')

@section('title', 'Historique Détaillé des Paiements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-history me-2"></i>Historique Détaillé des Paiements</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <label for="student_filter" class="form-label">Filtrer par Étudiant</label>
                            <select class="form-control" id="student_filter">
                                <option value="">Tous les étudiants</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="date_from" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_from">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="date_to" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_to">
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="filter_btn">
                                <i class="fas fa-filter me-2"></i>Filtrer
                            </button>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body text-center">
                                    <h6>Total Paiements</h6>
                                    <h4 class="mb-0">{{ $totalPayments ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <h6>Montant Total</h6>
                                    <h4 class="mb-0">{{ number_format($totalAmount ?? 0, 2, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body text-center">
                                    <h6>Moyenne par Paiement</h6>
                                    <h4 class="mb-0">{{ number_format($averageAmount ?? 0, 2, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body text-center">
                                    <h6>Ce mois</h6>
                                    <h4 class="mb-0">{{ $thisMonthPayments ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des paiements -->
                    @if($payments->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun paiement trouvé</h5>
                            <p class="text-muted">Aucun paiement ne correspond aux critères de filtrage.</p>
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
                                        <td>
                                            <div>{{ $payment->registration->student->full_name }}</div>
                                            <small class="text-muted">{{ $payment->registration->student->email }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $payment->registration->course->name }}</div>
                                            <small class="text-muted">{{ $payment->registration->course->level }}</small>
                                        </td>
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
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="Détails">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Affichage de {{ $payments->firstItem() }} à {{ $payments->lastItem() }} sur {{ $payments->total() }} paiements
                            </div>
                            <div>
                                {{ $payments->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    document.getElementById('filter_btn').addEventListener('click', function() {
        const studentId = document.getElementById('student_filter').value;
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        
        // Build query parameters
        let params = new URLSearchParams();
        if (studentId) params.append('student_id', studentId);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        // Redirect with filters
        window.location.href = "{{ route('payments.history') }}" + (params.toString() ? '?' + params.toString() : '');
    });
    
    // Load students for filter dropdown
    fetch('/api/students')
        .then(response => response.json())
        .then(students => {
            const select = document.getElementById('student_filter');
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.full_name;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading students:', error));
});
</script>
@endpush