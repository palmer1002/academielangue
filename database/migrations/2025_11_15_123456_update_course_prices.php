<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les prix des cours existants
        DB::table('courses')->where('name', 'Anglais Débutant')->update(['price' => 50000]);
        DB::table('courses')->where('name', 'Anglais Intermédiaire')->update(['price' => 50000]);
        DB::table('courses')->where('name', 'Anglais Avancé')->update(['price' => 50000]);
        DB::table('courses')->where('name', 'Espagnol Débutant')->update(['price' => 50000]);
        DB::table('courses')->where('name', 'Français Débutant')->update(['price' => 50000]);
        
        // Insérer un nouveau cours annuel
        DB::table('courses')->insert([
            'name' => 'Anglais Annuel',
            'level' => 'Annuel',
            'price' => 250000,
            'duration_days' => 365,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rétablir les prix des cours aux valeurs originales
        DB::table('courses')->where('name', 'Anglais Débutant')->update(['price' => 300]);
        DB::table('courses')->where('name', 'Anglais Intermédiaire')->update(['price' => 400]);
        DB::table('courses')->where('name', 'Anglais Avancé')->update(['price' => 500]);
        DB::table('courses')->where('name', 'Espagnol Débutant')->update(['price' => 280]);
        DB::table('courses')->where('name', 'Français Débutant')->update(['price' => 320]);
        
        // Supprimer le cours annuel
        DB::table('courses')->where('name', 'Anglais Annuel')->delete();
    }
};