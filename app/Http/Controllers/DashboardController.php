<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Données simulées pour le prototype
        $stats = [
            'totalStudents' => 156,
            'totalRegistrations' => 89,
            'totalRevenue' => 12500000,
            'pendingNeeds' => 12,
            'activeCourses' => 8,
            'completionRate' => 78
        ];

        // Inscriptions récentes simulées
        $recentRegistrations = [
            [
                'student' => 'KOKOROKO Raymond',
                'level' => 'Débutant A1',
                'start_date' => '2024-01-15',
                'status' => 'payé'
            ],
            [
                'student' => 'TEVI Josué',
                'level' => 'Intermédiaire B1',
                'start_date' => '2024-01-14',
                'status' => 'partiel'
            ],
            [
                'student' => 'TOSSOU Assouan',
                'level' => 'Avancé C1',
                'start_date' => '2024-01-13',
                'status' => 'payé'
            ],
            [
                'student' => 'SEWODO Gisele',
                'level' => 'Élémentaire A2',
                'start_date' => '2024-01-12',
                'status' => 'payé'
            ],
            [
                'student' => 'EKLOU Folly',
                'level' => 'Débutant A1',
                'start_date' => '2024-01-11',
                'status' => 'partiel'
            ]
        ];

        // Besoins en attente simulés
        $pendingNeeds = [
            [
                'student' => 'BOSSRO Emmunuel',
                'description' => 'Besoin de cours supplémentaires en grammaire',
                'date' => '2024-01-15'
            ],
            [
                'student' => 'KLANLENOU Arnaud',
                'description' => 'Demande de changement d\'horaire',
                'date' => '2024-01-14'
            ],
            [
                'student' => 'ALASSANI Fati ',
                'description' => 'Problème avec la plateforme en ligne',
                'date' => '2024-01-13'
            ]
        ];

        // Revenus par niveau simulés
        $revenueByLevel = [
            ['level' => 'Débutant A1', 'collected' => 4500000, 'En attente' => 1200000],
            ['level' => 'Élémentaire A2', 'collected' => 3800000, 'En attente' => 900000],
            ['level' => 'Intermédiaire B1', 'collected' => 5200000, 'En attente' => 800000],
            ['level' => 'Intermédiaire B2', 'collected' => 4100000, 'En attente' => 600000],
            ['level' => 'Avancé C1', 'collected' => 3500000, 'En attente' => 500000],
            ['level' => 'Maîtrise C2', 'collected' => 2800000, 'En attente' => 400000]
        ];

        // Correction du chemin de la vue pour correspondre à la structure réelle des répertoires
        return view('dashboard.dashboard', compact('stats', 'recentRegistrations', 'pendingNeeds', 'revenueByLevel'));
    }
}