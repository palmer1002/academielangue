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
        // First, rename the table if it exists with the french name
        if (Schema::hasTable('paiements') && !Schema::hasTable('payments')) {
            Schema::rename('paiements', 'payments');
        }

        // Then modify the table structure to match the model
        Schema::table('payments', function (Blueprint $table) {
            // Drop the old student_id column if it exists
            if (Schema::hasColumn('payments', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }

            // Add the registration_id column if it doesn't exist
            if (!Schema::hasColumn('payments', 'registration_id')) {
                $table->unsignedBigInteger('registration_id')->after('id');
                $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            }

            // Rename columns to match the model
            if (Schema::hasColumn('payments', 'montant') && !Schema::hasColumn('payments', 'amount')) {
                $table->renameColumn('montant', 'amount');
            }

            if (Schema::hasColumn('payments', 'date_paiement') && !Schema::hasColumn('payments', 'payment_date')) {
                $table->renameColumn('date_paiement', 'payment_date');
            }

            if (Schema::hasColumn('payments', 'methode') && !Schema::hasColumn('payments', 'payment_method')) {
                $table->renameColumn('methode', 'payment_method');
            }

            // Ensure all required columns exist
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
            // Revert column names
            if (Schema::hasColumn('payments', 'amount') && !Schema::hasColumn('payments', 'montant')) {
                $table->renameColumn('amount', 'montant');
            }

            if (Schema::hasColumn('payments', 'payment_date') && !Schema::hasColumn('payments', 'date_paiement')) {
                $table->renameColumn('payment_date', 'date_paiement');
            }

            if (Schema::hasColumn('payments', 'payment_method') && !Schema::hasColumn('payments', 'methode')) {
                $table->renameColumn('payment_method', 'methode');
            }

            // Remove registration_id and add back student_id
            if (Schema::hasColumn('payments', 'registration_id')) {
                $table->dropForeign(['registration_id']);
                $table->dropColumn('registration_id');
            }

            // Add student_id back
            if (!Schema::hasColumn('payments', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
                $table->foreign('student_id')->references('id')->on('etudiants')->onDelete('cascade');
            }
        });

        // Rename table back to french name if needed
        if (Schema::hasTable('payments') && !Schema::hasTable('paiements')) {
            Schema::rename('payments', 'paiements');
        }
    }
};