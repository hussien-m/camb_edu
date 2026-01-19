<?php

use App\Http\Controllers\Student\Auth\LoginController;
use App\Http\Controllers\Student\Auth\RegisterController;
use App\Http\Controllers\Student\Auth\VerificationController;
use App\Http\Controllers\Student\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

// Guest Routes (Login & Register)
Route::middleware('student.guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware(['rate.limit:5,5', 'recaptcha:0.6']); // 5 attempts per 5 minutes, reCAPTCHA protection
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])
        ->middleware(['rate.limit:3,10', 'honeypot']); // 3 attempts per 10 minutes

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware(['rate.limit:3,10'])
        ->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])
        ->middleware(['rate.limit:5,10'])
        ->name('password.update');
});

// Email Verification Routes (accessible by logged in but unverified students)
Route::middleware('student')->group(function () {
    Route::get('email/verify', [VerificationController::class, 'notice'])->name('verify.notice');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verify.resend');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Resend verification email (for non-logged in users)
Route::middleware('student.guest')->group(function () {
    Route::get('email/resend', [VerificationController::class, 'showResendRequest'])->name('verify.resend.request');
    Route::post('email/resend', [VerificationController::class, 'resendByEmail'])->name('verify.resend.email');
});

// Email verification link (no auth required, but signed URL required)
Route::get('email/verify/{id}/{token?}', [VerificationController::class, 'verify'])
    ->name('verify.email')
    ->middleware('signed');

// Protected Student Routes (must be verified)
Route::middleware(['student', 'student.verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // My Courses
    Route::get('courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/content', [App\Http\Controllers\Student\CourseController::class, 'content'])
        ->name('courses.content');
    Route::post('courses/{course}/enroll', [App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');

    // Exams
    Route::get('exams/calendar', [App\Http\Controllers\Student\ExamController::class, 'calendar'])->name('exams.calendar');
    Route::get('exams/{exam}', [App\Http\Controllers\Student\ExamController::class, 'show'])->name('exams.show');
    Route::post('exams/{exam}/start', [App\Http\Controllers\Student\ExamController::class, 'start'])->name('exams.start');
    Route::get('attempts/{attempt}', [App\Http\Controllers\Student\ExamController::class, 'take'])->name('exams.take');
    Route::post('attempts/{attempt}/answer', [App\Http\Controllers\Student\ExamController::class, 'submitAnswer'])->name('exams.answer');
    Route::post('attempts/{attempt}/submit', [App\Http\Controllers\Student\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('attempts/{attempt}/result', [App\Http\Controllers\Student\ExamController::class, 'result'])->name('exams.result');

    // Certificates
    Route::get('certificates', [App\Http\Controllers\Student\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [App\Http\Controllers\Student\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('certificates/{certificate}/download', [App\Http\Controllers\Student\CertificateController::class, 'download'])->name('certificates.download');
});
