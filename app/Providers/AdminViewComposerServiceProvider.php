<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\CourseInquiry;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Compose sidebar with cached statistics
        View::composer('admin.layouts.sidebar', function ($view) {
            $view->with([
                'unreadCount' => Cache::remember('admin.unread_messages', 60, function () {
                    return Contact::where('is_read', false)->count();
                }),
                'newInquiriesCount' => Cache::remember('admin.new_inquiries', 60, function () {
                    return CourseInquiry::where('status', 'new')->count();
                }),
                'pendingCount' => Cache::remember('admin.pending_students', 60, function () {
                    return Student::where('status', 'pending')->count();
                }),
            ]);
        });

        // Compose navbar with cached statistics
        View::composer('admin.layouts.navbar', function ($view) {
            $view->with([
                'unreadCount' => Cache::remember('admin.unread_messages', 60, function () {
                    return Contact::where('is_read', false)->count();
                }),
            ]);
        });
    }
}

