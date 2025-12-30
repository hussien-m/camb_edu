<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockPublicDirectAccess
{
    /**
     * Handle an incoming request.
     *
     * Block direct access to /public/ directory
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the URL contains /public/ in the path
        $uri = $request->getRequestUri();
        
        // If URL starts with /public/ (case insensitive)
        if (preg_match('#^/public/#i', $uri)) {
            // Remove /public/ from the path
            $newPath = preg_replace('#^/public/#i', '/', $uri);
            
            // Redirect permanently (301) to the clean URL
            return redirect($newPath, 301);
        }

        return $next($request);
    }
}

