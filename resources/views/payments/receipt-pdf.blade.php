<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fc;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15);
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e3e6f0;
            padding-bottom: 20px;
        }
        
        .receipt-header h1 {
            color: #4e73df;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        
        .receipt-header p {
            color: #858796;
            margin: 0;
        }
        
        .info-group {
            margin-bottom: 15px;
            display: flex;
        }
        
        .info-label {
            font-weight: 600;
            width: 150px;
            color: #555;
        }
        
        .info-value {
            font-weight: 500;
            flex: 1;
        }
        
        .amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: #28a745;
        }
        
        .payment-details {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 0.375rem;
            margin: 20px 0;
        }
        
        .signature-section {
            margin-top: 40px;
            text-align: right;
            padding-top: 20px;
            border-top: 1px solid #e3e6f0;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.35rem;
        }
        
        .bg-success {
            color: #fff;
            background-color: #28a745;
        }
        
        .bg-warning {
            color: #fff;
            background-color: #ffc107;
        }
        
        .bg-danger {
            color: #fff;
            background-color: #dc3545;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 15px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                border: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- En-tête du reçu -->
        <div class="receipt-header">
            <h1>ACADEMIE DE LANGUES</h1>
            <p>Centre de formation linguistique</p>
            <p>Reçu de Paiement</p>
        </div>
        
        <!-- Informations du reçu -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">N° Reçu:</span>
                    <span class="info-value">{{ $payment->receipt_number }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Méthode:</span>
                    <span class="info-value">{{ $payment->payment_method_text }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Statut:</span>
                    <span class="info-value">
                        <span class="badge bg-{{ $payment->statut == 'payé' ? 'success' : ($payment->statut == 'en_attente' ? 'warning' : 'danger') }}">
                            {{ ucfirst($payment->statut) }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">Étudiant:</span>
                    <span class="info-value">{{ $payment->registration->student->full_name }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $payment->registration->student->email ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $payment->registration->student->phone ?? 'Non spécifié' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Détails du paiement -->
        <div class="payment-details">
            <h3>Détails du Paiement</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group">
                        <span class="info-label">Cours:</span>
                        <span class="info-value">{{ $payment->registration->course->name }}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Niveau:</span>
                        <span class="info-value">{{ $payment->registration->course->level }}</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Date d'Inscription:</span>
                        <span class="info-value">{{ $payment->registration->registration_date->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <span class="info-label">Montant Payé:</span>
                        <span class="info-value amount">{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Solde Restant:</span>
                        <span class="info-value">{{ number_format($payment->registration->remaining_amount, 2, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Total du Cours:</span>
                        <span class="info-value">{{ number_format($payment->registration->total_amount, 2, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        @if($payment->notes)
        <div class="notes-section">
            <h3>Commentaires</h3>
            <p>{{ $payment->notes }}</p>
        </div>
        @endif
        
        <!-- Signature -->
        <div class="signature-section">
            <p>Signature du responsable</p>
        </div>
    </div>
</body>
</html>