<?php
// app/Models/Need.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'title', 'description', 'priority', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Etudiant::class, 'student_id');
    }

    public function getPriorityTextAttribute()
    {
        $priorities = [
            'basse' => 'Basse',
            'moyenne' => 'Moyenne',
            'haute' => 'Haute'
        ];

        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'resolu' => 'Résolu'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}