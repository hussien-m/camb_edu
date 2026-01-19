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

        if (!config('auth.student_email_verification', true)) {
            return $next($request);
        }

        // Check if email is verified
        if (!$student->email_verified_at) {
            return redirect()->route('student.verify.notice')
                ->with('warning', 'Please verify your email address first.');
        }

        // Check if account is active
        if ($student->status !== 'active') {
            // If status is pending, redirect to verification page instead of logout
            if ($student->status === 'pending') {
                return redirect()->route('student.verify.notice')
                    ->with('info', 'Please verify your email address to activate your account. We\'ve sent a verification link to your email.');
            }
            
            // For other statuses (suspended, etc.), logout
            Auth::guard('student')->logout();
            return redirect()->route('student.login')
                ->with('error', 'Please check your email and click the activation link to activate your account.')
                ->with('account_status', 'pending');
        }

        return $next($request);
    }
}
