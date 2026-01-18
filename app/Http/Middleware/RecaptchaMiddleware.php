<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class RecaptchaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Verify Google reCAPTCHA v3 token
     */
    public function handle(Request $request, Closure $next, float $minScore = 0.5): Response
    {
        if (!config('services.recaptcha.enabled')) {
            return $next($request);
        }

        // Skip in local development
        if (app()->environment('local') && !config('services.recaptcha.enabled_locally')) {
            return $next($request);
        }

        $recaptchaToken = $request->input('recaptcha_token');

        if (!$recaptchaToken) {
            return response()->json([
                'error' => 'reCAPTCHA verification failed. Please refresh and try again.',
            ], 422);
        }

        // Verify with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        // Check if verification was successful
        if (!$result['success']) {
            $errorCodes = $result['error-codes'] ?? [];

            Log::warning('reCAPTCHA verification failed', [
                'ip' => $request->ip(),
                'error_codes' => $errorCodes,
                'full_response' => $result,
            ]);

            // More helpful error message
            $errorMessage = 'reCAPTCHA verification failed. Please try again.';
            if (in_array('hostname-not-allowed', $errorCodes)) {
                $errorMessage = 'Security verification failed. Domain not authorized.';
                Log::error('reCAPTCHA: Domain not authorized! Add cambridge-college.uk to Google reCAPTCHA Console.');
            }

            return response()->json([
                'error' => $errorMessage,
            ], 422);
        }

        // Check score (v3 only)
        if (isset($result['score']) && $result['score'] < $minScore) {
            Log::warning('reCAPTCHA score too low - Possible bot', [
                'ip' => $request->ip(),
                'score' => $result['score'],
                'action' => $result['action'] ?? 'unknown',
            ]);

            return response()->json([
                'error' => 'Security check failed. Please try again later.',
            ], 422);
        }

        // All good!
        return $next($request);
    }
}

