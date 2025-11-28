<?php

use App\Http\Controllers\Student\Auth\LoginController;
use App\Http\Controllers\Student\Auth\RegisterController;
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
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Protected Student Routes
Route::middleware('student')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // My Courses
    Route::get('courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::post('courses/{course}/enroll', [App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');

    // Exams
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
