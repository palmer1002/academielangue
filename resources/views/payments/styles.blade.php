<style>
    /* Variables de couleurs harmonisées */
    :root {
        --primary-color: #4a90e2;
        --success-color: #2ecc71;
        --warning-color: #f39c12;
        --info-color: #3498db;
        --danger-color: #e74c3c;
        --secondary-color: #7f8c8d;
    }
    
    /* Header harmonisé */
    header {
        background: linear-gradient(135deg, var(--primary-color), #357ABD);
    }
    
    /* Cartes de statistiques harmonisées */
    .total-revenue { border-top: 4px solid var(--primary-color); }
    .pending-payments { border-top: 4px solid var(--warning-color); }
    .paid-this-month { border-top: 4px solid var(--success-color); }
    .overdue-payments { border-top: 4px solid var(--danger-color); }
    
    /* Onglets harmonisés */
    .tab.active {
        background-color: #f0f7ff;
        border-bottom: 3px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    /* Boutons harmonisés */
    .btn-view {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-edit {
        background-color: var(--warning-color);
        color: white;
    }
    
    .btn-payment {
        background-color: var(--success-color);
        color: white;
    }
    
    .btn-download {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .btn-send {
        background-color: #9c27b0;
        color: white;
    }
    
    /* Badges de statut harmonisés */
    .status-paid {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .status-pending {
        background-color: #fff3e0;
        color: var(--warning-color);
    }
    
    .status-overdue {
        background-color: #ffebee;
        color: var(--danger-color);
    }
    
    .invoice-sent {
        background-color: #e3f2fd;
        color: var(--primary-color);
    }
    
    .invoice-paid {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .invoice-overdue {
        background-color: #ffebee;
        color: var(--danger-color);
    }
    
    /* Badges de cours avec couleurs harmonisées */
    .badge-anglais { background-color: var(--primary-color); }
    .badge-espagnol { background-color: var(--danger-color); }
    .badge-allemand { background-color: var(--success-color); }
    .badge-francais { background-color: #9b59b6; }
    .badge-italien { background-color: var(--warning-color); }
    .badge-portugais { background-color: #1abc9c; }
    .badge-chinois { background-color: #d35400; }
    .badge-japonais { background-color: #c0392b; }
    .badge-russe { background-color: var(--secondary-color); }
    .badge-arabe { background-color: #16a085; }
    
    /* Logo et titres harmonisés */
    .invoice-logo {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    /* Pagination active harmonisée */
    .pagination button.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    /* Options de paiement sélectionnées */
    .payment-option.selected {
        border-color: var(--primary-color);
        background-color: #f0f7ff;
    }
    </style>