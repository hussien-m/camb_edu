<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CourseInquiryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('test-push',function(){
    return "Test 4";
});

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [HomeController::class, 'search'])->name('courses.index');
Route::get('/courses/search', [HomeController::class, 'search'])->name('courses.search');
Route::get('/course/{category}/{level}/{course}', [HomeController::class, 'show'])->name('courses.show');
Route::get('/success-stories', [HomeController::class, 'successStories'])->name('success.stories');
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Course Inquiry
Route::post('/course/{course}/inquiry', [CourseInquiryController::class, 'store'])->name('course.inquiry.store');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

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
