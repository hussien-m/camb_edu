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

        $appUrl = config('app.url');
        $appScheme = $appUrl ? parse_url($appUrl, PHP_URL_SCHEME) : null;

        // Force HTTPS only when explicitly configured
        if (config('app.env') === 'production' && $appScheme === 'https' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Force www or non-www consistently (choose one)
        $host = $request->getHost();
        $preferWww = false; // Set to true if you prefer www.domain.com

        $scheme = $request->getScheme();
        if ($appScheme) {
            $scheme = $appScheme;
        }

        if ($preferWww && !str_starts_with($host, 'www.')) {
            return redirect($scheme . '://www.' . $host . $request->getRequestUri(), 301);
        } elseif (!$preferWww && str_starts_with($host, 'www.')) {
            $newHost = substr($host, 4);
            return redirect($scheme . '://' . $newHost . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}


