<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\Course;
use Carbon\Carbon;

class UpdateRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = Registration::all();
        
        foreach ($registrations as $registration) {
            $course = Course::find($registration->course_id);
            if ($course) {
                $startDate = $registration->registration_date ?? Carbon::today();
                $endDate = Carbon::parse($startDate)->addDays($course->duration_days);
                
                // Calculate total amount paid for this registration
                $amountPaid = $registration->payments()->sum('amount');
                
                $registration->update([
                    'total_amount' => $course->price,
                    'amount_paid' => $amountPaid,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
            }
        }
    }
}