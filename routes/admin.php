<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group and "admin" prefix.
|
*/

// Admin Authentication Routes
Route::middleware('admin.guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware(['rate.limit:5,5', 'recaptcha:0.7']); // Stricter protection for admin (score 0.7)
});

// Admin Protected Routes
Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Image Upload for Editor
    Route::post('upload-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('upload.image');

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Analytics
    Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/realtime', [App\Http\Controllers\Admin\AnalyticsController::class, 'realtime'])->name('analytics.realtime');

    // Course Categories
    Route::resource('categories', App\Http\Controllers\Admin\CourseCategoryController::class);

    // Course Levels
    Route::resource('levels', App\Http\Controllers\Admin\CourseLevelController::class);

    // Courses
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::post('courses/{course}/toggle-content-disabled', [App\Http\Controllers\Admin\CourseController::class, 'toggleContentDisabled'])->name('courses.toggle-content-disabled')->where('course', '[0-9]+');
    Route::post('bulk-actions/courses', [App\Http\Controllers\Admin\BulkActionController::class, 'courses'])->name('bulk.courses');
    Route::get('export/courses', [App\Http\Controllers\Admin\ExportController::class, 'courses'])->name('export.courses');

    // Enrollments
    Route::get('enrollments', [App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('enrollments/filter', [App\Http\Controllers\Admin\EnrollmentController::class, 'filter'])->name('enrollments.filter');
    Route::post('enrollments/{enrollment}/toggle-content-disabled', [App\Http\Controllers\Admin\EnrollmentController::class, 'toggleContentDisabled'])->name('enrollments.toggle-content-disabled');
    Route::post('enrollments/{enrollment}/toggle-exam-disabled', [App\Http\Controllers\Admin\EnrollmentController::class, 'toggleExamDisabled'])->name('enrollments.toggle-exam-disabled');

    // Pages
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);

    // Success Stories
    Route::resource('stories', App\Http\Controllers\Admin\SuccessStoryController::class);

    // Contacts
    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('contacts/{contact}/mark-read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-read');
    Route::post('bulk-actions/contacts', [App\Http\Controllers\Admin\BulkActionController::class, 'contacts'])->name('bulk.contacts');
    Route::get('export/contacts', [App\Http\Controllers\Admin\ExportController::class, 'contacts'])->name('export.contacts');

    // Course Inquiries
    Route::get('inquiries', [App\Http\Controllers\Admin\CourseInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [App\Http\Controllers\Admin\CourseInquiryController::class, 'show'])->name('inquiries.show');
    Route::post('inquiries/{inquiry}/status', [App\Http\Controllers\Admin\CourseInquiryController::class, 'updateStatus'])->name('inquiries.update-status');
    Route::delete('inquiries/{inquiry}', [App\Http\Controllers\Admin\CourseInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Banners
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

    // Ads Management
    Route::resource('ads', App\Http\Controllers\Admin\AdController::class);
    Route::post('ads/{ad}/toggle-status', [App\Http\Controllers\Admin\AdController::class, 'toggleStatus'])->name('ads.toggle-status');

    // Features
    Route::resource('features', App\Http\Controllers\Admin\FeatureController::class);

    // Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Email Settings (SMTP)
    Route::get('email-settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.email');
    Route::post('email-settings/save', [App\Http\Controllers\Admin\SettingsController::class, 'save'])->name('settings.save');
    Route::post('email-settings/test', [App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('settings.test-email');

    // Newsletter Subscribers
    Route::get('newsletter', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('newsletter/{id}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('newsletter/export', [App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');

    // Students Management
    Route::get('students', [App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}/edit', [App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
    Route::delete('students/{student}', [App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');
    Route::put('students/{student}/status', [App\Http\Controllers\Admin\StudentController::class, 'updateStatus'])->name('students.update-status');
    Route::post('bulk-actions/students', [App\Http\Controllers\Admin\BulkActionController::class, 'students'])->name('bulk.students');
    Route::get('export/students', [App\Http\Controllers\Admin\ExportController::class, 'students'])->name('export.students');

    // Verification Reminders
    Route::get('verification-reminders', [App\Http\Controllers\Admin\VerificationReminderController::class, 'index'])->name('verification-reminders.index');
    Route::post('verification-reminders/send-all', [App\Http\Controllers\Admin\VerificationReminderController::class, 'sendToAll'])->name('verification-reminders.send-all');
    Route::post('verification-reminders/send-recent', [App\Http\Controllers\Admin\VerificationReminderController::class, 'sendToRecent'])->name('verification-reminders.send-recent');
    Route::post('verification-reminders/send/{student}', [App\Http\Controllers\Admin\VerificationReminderController::class, 'sendToStudent'])->name('verification-reminders.send-student');

    // Exams Management
    Route::resource('exams', App\Http\Controllers\Admin\ExamController::class);
    Route::get('exams/{exam}/assignments/students', [App\Http\Controllers\Admin\ExamAssignmentController::class, 'searchStudents'])
        ->name('exams.assignments.students');
    Route::post('exams/{exam}/assignments', [App\Http\Controllers\Admin\ExamAssignmentController::class, 'store'])
        ->name('exams.assignments.store');
    Route::delete('exams/{exam}/assignments/{assignment}', [App\Http\Controllers\Admin\ExamAssignmentController::class, 'destroy'])
        ->name('exams.assignments.destroy');

    // Questions Management
    Route::get('exams/{exam}/questions/create', [App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('questions.create');
    Route::post('exams/{exam}/questions', [App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('questions.store');
    Route::post('exams/{exam}/questions/quick-add', [App\Http\Controllers\Admin\QuestionController::class, 'quickAdd'])->name('questions.quick-add');
    Route::get('exams/{exam}/questions/{question}/edit', [App\Http\Controllers\Admin\QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('exams/{exam}/questions/{question}', [App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('questions.update');
    Route::post('exams/{exam}/questions/{question}/quick-update', [App\Http\Controllers\Admin\QuestionController::class, 'quickUpdate'])->name('questions.quick-update');
    Route::delete('exams/{exam}/questions/{question}', [App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('questions.destroy');

    // Exam Results Management
    Route::get('exam-results', [App\Http\Controllers\Admin\ExamResultController::class, 'index'])->name('exam-results.index');
    Route::post('exam-results/filter', [App\Http\Controllers\Admin\ExamResultController::class, 'filter'])->name('exam-results.filter');
    Route::get('exam-results/{id}', [App\Http\Controllers\Admin\ExamResultController::class, 'show'])->name('exam-results.show');
    Route::get('exam-results/{id}/edit', [App\Http\Controllers\Admin\ExamResultController::class, 'edit'])->name('exam-results.edit');
    Route::put('exam-results/{id}', [App\Http\Controllers\Admin\ExamResultController::class, 'update'])->name('exam-results.update');
    Route::post('exam-results/{id}/recalculate', [App\Http\Controllers\Admin\ExamResultController::class, 'recalculate'])->name('exam-results.recalculate');
    Route::post('exam-results/{id}/toggle-certificate', [App\Http\Controllers\Admin\ExamResultController::class, 'toggleCertificate'])
        ->name('exam-results.toggle-certificate');
    Route::post('exam-results/enable-certificates', [App\Http\Controllers\Admin\ExamResultController::class, 'enableCertificatesForExam'])
        ->name('exam-results.enable-certificates');
    Route::delete('exam-results/{id}', [App\Http\Controllers\Admin\ExamResultController::class, 'destroy'])->name('exam-results.destroy');

    // Activity Log
    Route::get('activity-log', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');

    // Exam Reminders Management
    Route::prefix('exam-reminders')->name('exam-reminders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ExamReminderController::class, 'index'])->name('index');
        Route::post('/create', [App\Http\Controllers\Admin\ExamReminderController::class, 'create'])->name('create');
        Route::post('/send', [App\Http\Controllers\Admin\ExamReminderController::class, 'send'])->name('send');
        Route::post('/{reminder}/send', [App\Http\Controllers\Admin\ExamReminderController::class, 'sendReminder'])->name('send-one');
        Route::delete('/delete-unsent', [App\Http\Controllers\Admin\ExamReminderController::class, 'deleteUnsent'])->name('delete-unsent');
        Route::post('/test-email', [App\Http\Controllers\Admin\ExamReminderController::class, 'testEmail'])->name('test-email');
    });
});
