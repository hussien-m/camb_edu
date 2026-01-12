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
            // If status is pending, redirect to verification page
            if ($student->status === 'pending') {
                return redirect()->route('student.verify.notice')
                    ->with('info', 'Please verify your email address to activate your account. We\'ve sent a verification link to your email.');
            }
            
            return redirect()->route('student.login')->with('error', 'Your account has been deactivated. Please contact support for assistance.');
        }

        return $next($request);
    }
}
