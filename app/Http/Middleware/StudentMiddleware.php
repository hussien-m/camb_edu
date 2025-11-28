<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('student')->check()) {
            return redirect()->route('student.login')->with('error', 'Please login to access student area');
        }

        $student = auth()->guard('student')->user();

        if ($student->status !== 'active') {
            auth()->guard('student')->logout();
            return redirect()->route('student.login')->with('error', 'Your account is not active. Please contact administration.');
        }

        return $next($request);
    }
}
