<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalUrlMiddleware
{
    /**
     * Handle an incoming request.
     * Enforce canonical URLs to prevent duplicate content issues
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for API, admin, and AJAX requests
        if ($request->is('api/*') || $request->is('admin/*') || $request->ajax()) {
            return $next($request);
        }

        $url = $request->url();
        $query = $request->query();

        // Remove trailing slash (except for root)
        if ($request->path() !== '/' && str_ends_with($request->path(), '/')) {
            $canonicalUrl = rtrim($url, '/');
            if (!empty($query)) {
                $canonicalUrl .= '?' . http_build_query($query);
            }
            return redirect($canonicalUrl, 301);
        }

        // Force HTTPS in production
        if (config('app.env') === 'production' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Force www or non-www consistently (choose one)
        $host = $request->getHost();
        $preferWww = false; // Set to true if you prefer www.domain.com

        if ($preferWww && !str_starts_with($host, 'www.')) {
            return redirect('https://www.' . $host . $request->getRequestUri(), 301);
        } elseif (!$preferWww && str_starts_with($host, 'www.')) {
            $newHost = substr($host, 4);
            return redirect('https://' . $newHost . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}

