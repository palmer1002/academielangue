<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'amount',
        'payment_date',
        'payment_method',
        'statut',
        'receipt_number',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    // Générer un numéro de reçu unique
    public static function generateReceiptNumber()
    {
        do {
            $receiptNumber = 'REC-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }

    // Scope pour les paiements en attente
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // Scope pour les paiements payés
    public function scopePayes($query)
    {
        return $query->where('statut', 'payé');
    }

    // Scope pour les paiements en retard
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'retard');
    }

    // Obtenir le texte de la méthode de paiement
    public function getPaymentMethodTextAttribute()
    {
        $methods = [
            'cash' => 'Espèces',
            'check' => 'Chèque',
            'transfer' => 'Virement',
            'card' => 'Carte bancaire'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}