<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Protect against spam and brute force attacks
     */
    public function handle(Request $request, Closure $next, string $maxAttempts = '5', string $decayMinutes = '1'): Response
    {
        $key = $this->resolveRequestSignature($request);
        
        // Check if rate limit exceeded
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            // Log suspicious activity
            \Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'error' => 'Too many attempts. Please try again in ' . ceil($seconds / 60) . ' minute(s).',
                'retry_after' => $seconds,
            ], 429);
        }

        // Hit the rate limiter
        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Use IP + Route for uniqueness
        return sha1(
            $request->ip() . '|' . $request->path()
        );
    }
}

