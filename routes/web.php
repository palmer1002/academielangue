<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentNeedController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Test route for debugging
Route::get('/test-students', function() {
    $students = \App\Models\Etudiant::whereHas('registrations', function($query) {
        $query->where('status', 'active')
              ->whereRaw('amount_paid < total_amount');
    })->with(['registrations.course'])->get();
    
    return response()->json([
        'count' => $students->count(),
        'students' => $students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->email,
                'registrations_count' => $student->registrations->count(),
                'registrations' => $student->registrations->map(function($reg) {
                    return [
                        'id' => $reg->id,
                        'course' => $reg->course->name ?? 'N/A',
                        'total_amount' => $reg->total_amount,
                        'amount_paid' => $reg->amount_paid,
                        'remaining' => $reg->remaining_amount,
                    ];
                }),
            ];
        }),
    ]);
});

Route::get('/test-registrations/{studentId}', function($studentId) {
    $registrations = \App\Models\Registration::with('course')
        ->where('student_id', $studentId)
        ->where('status', 'active')
        ->whereRaw('amount_paid < total_amount')
        ->get();
    
    return response()->json([
        'count' => $registrations->count(),
        'registrations' => $registrations->map(function($reg) {
            return [
                'id' => $reg->id,
                'course_name' => $reg->course->name ?? 'N/A',
                'course_level' => $reg->course->level ?? 'N/A',
                'total_amount' => $reg->total_amount,
                'amount_paid' => $reg->amount_paid,
                'remaining_amount' => $reg->remaining_amount,
            ];
        }),
    ]);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Students
    Route::resource('students', StudentController::class);
    
    // Registrations
    Route::resource('registrations', RegistrationController::class);
    
    // Payments - Routes complètes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::get('/payments/select-registration', [PaymentController::class, 'selectRegistration'])->name('payments.select-registration');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('/payments/{payment}/print', [PaymentController::class, 'printReceipt'])->name('payments.print');
    Route::get('/payments/{payment}/download', [PaymentController::class, 'downloadReceipt'])->name('payments.download');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/{payment}/marquer-paye', [PaymentController::class, 'marquerPaye'])->name('payments.marquer-paye');
    
    // Student Needs
    Route::resource('needs', StudentNeedController::class);
    Route::patch('/needs/{need}/status', [StudentNeedController::class, 'updateStatus'])->name('needs.updateStatus');
    
    // Reports
    Route::get('/reports/payments-by-level', [ReportController::class, 'paymentsByLevel'])->name('reports.payments-by-level');
    Route::get('/reports/student-balances', [ReportController::class, 'studentBalances'])->name('reports.student-balances');
    
    // API Routes for AJAX
    Route::get('/api/student-registrations/{studentId}', [PaymentController::class, 'getStudentRegistrations']);
    Route::get('/api/registration-details/{registrationId}', [PaymentController::class, 'getRegistrationDetails']);
    Route::get('/students/{etudiant}/registrations', [StudentController::class, 'registrations'])->name('students.registrations');
    Route::get('/api/student-info/{etudiant}', [PaymentController::class, 'getStudentInfo']);
    Route::get('/api/students', [PaymentController::class, 'getAllStudents']);
});

Route::get('/', function () {
    return redirect('/dashboard');
});