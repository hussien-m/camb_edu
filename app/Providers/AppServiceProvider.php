<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();

        // Share settings with all views
        try {
            View::share('settings', Setting::getAllSettings());

            // Share categories and levels for navigation
            View::composer('frontend.layouts.app', function ($view) {
                $view->with('categories', \App\Models\CourseCategory::all());
                $view->with('levels', \App\Models\CourseLevel::orderBy('sort_order')->get());
                $view->with('pages', \App\Models\Page::where('is_published', true)
                    ->orderBy('title')
                    ->limit(10)
                    ->get());
            });
        } catch (\Exception $e) {
            // If settings table doesn't exist yet (during migration)
            View::share('settings', []);
        }
    }
}
