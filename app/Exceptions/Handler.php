<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle 404 errors properly to avoid Soft 404
        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            // For AJAX requests, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Resource not found',
                    'message' => 'The requested resource was not found on this server.'
                ], 404);
            }

            // For web requests, return proper 404 view with correct status code
            return response()->view('errors.404', [
                'exception' => $exception
            ], 404);
        }

        // Handle 403 Forbidden
        if ($this->isHttpException($exception) && $exception->getStatusCode() === 403) {
            return response()->view('errors.403', [], 403);
        }

        // Handle 500 Server Error
        if ($this->isHttpException($exception) && $exception->getStatusCode() === 500) {
            if (config('app.debug')) {
                return parent::render($request, $exception);
            }
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $exception);
    }
}


