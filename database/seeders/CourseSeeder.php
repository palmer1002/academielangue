<?php
// database/seeders/CourseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['name' => 'Anglais Débutant', 'level' => 'Débutant', 'price' => 50000, 'duration_days' => 90],
            ['name' => 'Anglais Intermédiaire', 'level' => 'Intermédiaire', 'price' => 50000, 'duration_days' => 90],
            ['name' => 'Anglais Avancé', 'level' => 'Avancé', 'price' => 50000, 'duration_days' => 90],
            ['name' => 'Espagnol Débutant', 'level' => 'Débutant', 'price' => 50000, 'duration_days' => 90],
            ['name' => 'Français Débutant', 'level' => 'Débutant', 'price' => 50000, 'duration_days' => 90],
            ['name' => 'Anglais Annuel', 'level' => 'Annuel', 'price' => 250000, 'duration_days' => 365],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}