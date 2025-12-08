<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Notifications\StudentResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function showLinkRequestForm()
    {
        return view('student.auth.forgot-password');
    }

    /**
     * Handle sending the password reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
        ], [
            'email.exists' => 'We could not find an account with that email address.',
        ]);

        try {
            $student = Student::where('email', $request->email)->first();

            if (!$student) {
                return back()->withErrors(['email' => 'Student not found.']);
            }

            // Delete any existing token for this email
            DB::table('student_password_reset_tokens')->where('email', $request->email)->delete();

            // Create new token
            $token = Str::random(64);

            DB::table('student_password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);

            // Send notification synchronously (not queued)
            try {
                $student->notify(new StudentResetPasswordNotification($token));
            } catch (\Exception $mailError) {
                \Log::error('Email sending failed: ' . $mailError->getMessage(), [
                    'email' => $request->email,
                    'trace' => $mailError->getTraceAsString()
                ]);

                // Delete the token if email fails
                DB::table('student_password_reset_tokens')->where('email', $request->email)->delete();

                return back()->withErrors(['email' => 'Failed to send email. Please check your email configuration or contact support.']);
            }

            return back()->with('success', 'We have emailed your password reset link! Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage(), [
                'email' => $request->email ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the password reset form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('student.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle the password reset.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:students,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.exists' => 'We could not find an account with that email address.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Find the token
        $record = DB::table('student_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid password reset request. Please request a new link.']);
        }

        // Check if token is valid
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid password reset token. Please request a new link.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('student_password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Password reset link has expired. Please request a new one.']);
        }

        // Update the password
        $student = Student::where('email', $request->email)->first();
        $student->password = Hash::make($request->password);
        $student->save();

        // Delete the token
        DB::table('student_password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('student.login')
            ->with('success', 'Your password has been reset successfully! You can now login with your new password.');
    }
}
