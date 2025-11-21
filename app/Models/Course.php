<?php
// app/Models/Course.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'price', 'duration_days'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Registration::class);
    }

    public function getTotalRevenueAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getActiveRegistrationsCountAttribute()
    {
        return $this->registrations()->where('status', 'active')->count();
    }
}