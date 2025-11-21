<?php
// app/Models/Etudiant.php
namespace App\Models;

class Etudiant extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 'date_of_birth'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}