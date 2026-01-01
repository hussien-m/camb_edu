<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('student.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $student = Auth::guard('student')->user();

            if ($student->status !== 'active') {
                Auth::guard('student')->logout();

                $statusMessage = $student->status === 'pending'
                    ? 'Your account is pending approval. Please verify your email address or contact our support team for assistance.'
                    : 'Your account has been deactivated. Please contact our support team for assistance.';

                return back()
                    ->with('error', $statusMessage)
                    ->with('account_status', $student->status)
                    ->withInput();
            }

            // Update last login info
            $student->last_login_at = now();
            $student->last_login_ip = $request->ip();
            $student->save();

            return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        // Regenerate the CSRF token but do NOT invalidate entire session
        // This prevents affecting other guard sessions (like admin)
        $request->session()->regenerateToken();

        return redirect()->route('student.login')->with('success', 'You have been logged out successfully.');
    }
}
