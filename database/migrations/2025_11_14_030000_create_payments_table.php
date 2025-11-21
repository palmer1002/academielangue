<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->decimal('montant', 10, 2);
            $table->string('methode')->default('espèces'); // espèces, virement, carte, chèque
            $table->date('date_paiement');
            $table->date('date_echeance')->nullable();
            $table->string('statut')->default('en_attente'); // payé, en_attente, annulé, retard
            $table->text('notes')->nullable();
            $table->string('facture_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};