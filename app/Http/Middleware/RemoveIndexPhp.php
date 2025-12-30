<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveIndexPhp
{
    /**
     * Handle an incoming request.
     * Remove index.php from URLs if present
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if URL contains index.php
        $requestUri = $request->getRequestUri();

        if (str_contains($requestUri, '/index.php')) {
            $newUri = str_replace('/index.php', '', $requestUri);
            return redirect($newUri, 301);
        }

        return $next($request);
    }
}

