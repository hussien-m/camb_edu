<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateContent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add X-Robots-Tag header for duplicate content prevention
        if ($response instanceof Response) {
            // Check if this is a paginated page (page > 1)
            if ($request->has('page') && $request->get('page') > 1) {
                // Tell search engines not to index paginated pages
                $response->headers->set('X-Robots-Tag', 'noindex, follow');
            }

            // Check for query parameters that might create duplicate content
            $queryParams = $request->query();
            $allowedParams = ['category', 'level', 'search', 'page'];
            
            foreach ($queryParams as $key => $value) {
                if (!in_array($key, $allowedParams)) {
                    // Unexpected query parameter - prevent indexing
                    $response->headers->set('X-Robots-Tag', 'noindex, follow');
                    break;
                }
            }
        }

        return $response;
    }
}


