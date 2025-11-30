<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student.login');
        }

        // Check if email is verified
        if (!$student->email_verified_at) {
            return redirect()->route('student.verify.notice')
                ->with('warning', 'Please verify your email address first.');
        }

        // Check if account is active
        if ($student->status !== 'active') {
            Auth::guard('student')->logout();
            return redirect()->route('student.login')
                ->with('error', 'Your account is not active. Please check your email and click the activation link.');
        }

        return $next($request);
    }
}
