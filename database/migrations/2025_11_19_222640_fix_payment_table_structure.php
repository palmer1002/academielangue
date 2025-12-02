<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // D'abord, renommer la table si elle existe avec le nom français
        if (Schema::hasTable('paiements') && !Schema::hasTable('payments')) {
            Schema::rename('paiements', 'payments');
        }

        // Ensuite, modifier la structure de la table pour correspondre au modèle
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer l'ancienne colonne student_id si elle existe
            if (Schema::hasColumn('payments', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }

            // Ajouter la colonne registration_id si elle n'existe pas
            if (!Schema::hasColumn('payments', 'registration_id')) {
                $table->unsignedBigInteger('registration_id')->after('id');
                $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            }

            // Renommer les colonnes pour correspondre au modèle
            if (Schema::hasColumn('payments', 'montant') && !Schema::hasColumn('payments', 'amount')) {
                $table->renameColumn('montant', 'amount');
            }

            if (Schema::hasColumn('payments', 'date_paiement') && !Schema::hasColumn('payments', 'payment_date')) {
                $table->renameColumn('date_paiement', 'payment_date');
            }

            if (Schema::hasColumn('payments', 'methode') && !Schema::hasColumn('payments', 'payment_method')) {
                $table->renameColumn('methode', 'payment_method');
            }

            // S'assurer que toutes les colonnes requises existent
            if (!Schema::hasColumn('payments', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('payments', 'statut')) {
                $table->string('statut')->default('en_attente')->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rétablir les noms de colonnes
            if (Schema::hasColumn('payments', 'amount') && !Schema::hasColumn('payments', 'montant')) {
                $table->renameColumn('amount', 'montant');
            }

            if (Schema::hasColumn('payments', 'payment_date') && !Schema::hasColumn('payments', 'date_paiement')) {
                $table->renameColumn('payment_date', 'date_paiement');
            }

            if (Schema::hasColumn('payments', 'payment_method') && !Schema::hasColumn('payments', 'methode')) {
                $table->renameColumn('payment_method', 'methode');
            }

            // Supprimer registration_id et rajouter student_id
            if (Schema::hasColumn('payments', 'registration_id')) {
                $table->dropForeign(['registration_id']);
                $table->dropColumn('registration_id');
            }

            // Rajouter student_id
            if (!Schema::hasColumn('payments', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
                $table->foreign('student_id')->references('id')->on('etudiants')->onDelete('cascade');
            }
        });

        // Renommer la table avec le nom français si nécessaire
        if (Schema::hasTable('payments') && !Schema::hasTable('paiements')) {
            Schema::rename('payments', 'paiements');
        }
    }
};