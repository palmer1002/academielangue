<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="row">
    <!-- Statistics Cards avec couleurs harmonisées -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Étudiants</h5>
                        <h2 class="text-primary">{{ $stats['totalStudents'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Inscriptions</h5>
                        <h2 class="text-success">{{ $stats['totalRegistrations'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Actifs</h5>
                        <h2 class="text-warning">{{ $stats['activeCourses'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Revenus</h5>
                        <h2 class="text-info">{{ number_format($stats['totalRevenue'] / 100, 2) }} FCFA</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Registrations -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Inscriptions Récentes</h5>
            </div>
            <div class="card-body">
                @foreach($recentRegistrations as $registration)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <h6 class="mb-0">{{ $registration['student'] }}</h6>
                        <small class="text-muted">{{ $registration['level'] }}</small>
                    </div>
                    <span class="badge bg-{{ $registration['status'] == 'payé' ? 'success' : 'secondary' }}">
                        {{ $registration['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pending Needs -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Besoins en Attente</h5>
            </div>
            <div class="card-body">
                @foreach($pendingNeeds as $need)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <h6 class="mb-0">{{ $need['student'] }}</h6>
                        <small class="text-muted">{{ Str::limit($need['description'], 50) }}</small>
                    </div>
                    <span class="badge bg-secondary">
                        {{ date('d/m/Y', strtotime($need['date'])) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
/* Styles harmonisés avec la page de paiement */
.stat-card {
    border-top: 4px solid;
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 10px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

/* Couleurs harmonisées avec la page de paiement */
.card .text-primary { color: #4a90e2 !important; }
.card .text-success { color: #2ecc71 !important; }
.card .text-warning { color: #f39c12 !important; }
.card .text-info { color: #3498db !important; }

.bg-primary { background-color: #4a90e2 !important; }
.bg-success { background-color: #2ecc71 !important; }
.bg-warning { background-color: #f39c12 !important; }
.bg-info { background-color: #3498db !important; }

.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    border: none;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    font-weight: 600;
}
</style>
@endsection