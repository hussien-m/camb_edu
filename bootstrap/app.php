<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
            'student' => \App\Http\Middleware\StudentMiddleware::class,
            'student.guest' => \App\Http\Middleware\RedirectIfStudent::class,
            'student.verified' => \App\Http\Middleware\EnsureStudentIsVerified::class,
            
            // Anti-Spam Middleware
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'honeypot' => \App\Http\Middleware\HoneypotMiddleware::class,
            'recaptcha' => \App\Http\Middleware\RecaptchaMiddleware::class,
        ]);

        // Security Middleware - MUST BE FIRST
        $middleware->prependToGroup('web', \App\Http\Middleware\BlockPublicDirectAccess::class);

        // SEO Middleware - Must be first for proper URL handling
        $middleware->prependToGroup('web', \App\Http\Middleware\RemoveIndexPhp::class);
        $middleware->prependToGroup('web', \App\Http\Middleware\CanonicalUrlMiddleware::class);
        $middleware->prependToGroup('web', \App\Http\Middleware\PreventDuplicateContent::class);

        // Redirect old course URLs to new SEO-friendly URLs
        $middleware->prependToGroup('web', \App\Http\Middleware\RedirectOldCourseUrls::class);

        // Track page views on web routes
        $middleware->appendToGroup('web', \App\Http\Middleware\TrackPageViews::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
