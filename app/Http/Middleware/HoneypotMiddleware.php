<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Honeypot trap for spam bots
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if honeypot field is filled
        if ($request->filled('website_url') || $request->filled('phone_number_confirm')) {
            // Bot detected! Log and block
            \Log::warning('Honeypot triggered - Bot detected', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'honeypot_fields' => [
                    'website_url' => $request->input('website_url'),
                    'phone_number_confirm' => $request->input('phone_number_confirm'),
                ],
            ]);

            // Return success to fool the bot
            // But don't actually process the request
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your submission!',
            ], 200);
        }

        return $next($request);
    }
}

