<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CourseInquiryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [HomeController::class, 'search'])->name('courses.index');
Route::get('/courses/search', [HomeController::class, 'search'])->name('courses.search');
Route::get('/courses/level/{level}', [HomeController::class, 'filterByLevel'])->name('courses.level');
Route::get('/courses/category/{category}', [HomeController::class, 'filterByCategory'])->name('courses.category');
Route::get('/courses/category/{category}/level/{level}', [HomeController::class, 'filterByCategoryAndLevel'])->name('courses.category.level');
Route::get('/course/{category}/{level}/{course}', [HomeController::class, 'show'])->name('courses.show');
Route::get('/success-stories', [HomeController::class, 'successStories'])->name('success.stories');
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');

// Contact Form (with anti-spam protection)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware(['rate.limit:3,5', 'honeypot', 'recaptcha:0.5'])
    ->name('contact.store');

// Course Inquiry (with anti-spam protection)
Route::post('/course/{course}/inquiry', [CourseInquiryController::class, 'store'])
    ->middleware(['rate.limit:3,5', 'honeypot', 'recaptcha:0.5'])
    ->name('course.inquiry.store');

// Newsletter Subscription (with anti-spam protection)
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware(['rate.limit:5,1', 'honeypot', 'recaptcha:0.5'])
    ->name('newsletter.subscribe');

// Ad Tracking Routes
Route::get('/ad/{ad}/click', [App\Http\Controllers\Frontend\AdController::class, 'trackClick'])->name('ad.click');
Route::get('/ad/{ad}/view', [App\Http\Controllers\Frontend\AdController::class, 'trackView'])->name('ad.view');

// Storage Link Route (for shared hosting)
Route::get('/storage-link', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (file_exists($link)) {
        return 'Storage link already exists!';
    }

    symlink($target, $link);
    return 'Storage link created successfully!';
});

// User Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    require __DIR__.'/student.php';
});

require __DIR__.'/auth.php';
