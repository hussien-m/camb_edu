<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
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
    Route::post('login', [LoginController::class, 'login']);
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

    // Course Categories
    Route::resource('categories', App\Http\Controllers\Admin\CourseCategoryController::class);

    // Course Levels
    Route::resource('levels', App\Http\Controllers\Admin\CourseLevelController::class);

    // Courses
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);

    // Pages
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);

    // Success Stories
    Route::resource('stories', App\Http\Controllers\Admin\SuccessStoryController::class);

    // Contacts
    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('contacts/{contact}/mark-read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-read');

    // Banners
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

    // Features
    Route::resource('features', App\Http\Controllers\Admin\FeatureController::class);

    // Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
