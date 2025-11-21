<?php
// app/Models/Registration.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'registration_date', 'start_date', 'end_date', 
        'total_amount', 'amount_paid', 'status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Etudiant::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }

    public function getRemainingDaysAttribute()
    {
        $endDate = Carbon::parse($this->end_date);
        $today = Carbon::today();
        
        if ($today->gt($endDate)) {
            return 0;
        }
        
        return $today->diffInDays($endDate);
    }

    public function getProgressAttribute()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $today = Carbon::today();
        
        $totalDays = $startDate->diffInDays($endDate);
        $daysPassed = $startDate->diffInDays($today);
        
        if ($daysPassed <= 0) return 0;
        if ($daysPassed >= $totalDays) return 100;
        
        return round(($daysPassed / $totalDays) * 100);
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->amount_paid >= $this->total_amount) {
            return 'paid';
        } elseif ($this->amount_paid > 0) {
            return 'partial';
        } else {
            return 'unpaid';
        }
    }
}