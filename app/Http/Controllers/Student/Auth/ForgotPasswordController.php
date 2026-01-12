<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Notifications\StudentResetPasswordNotification;
use App\Services\Mail\ProfessionalMailService;
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

            // Send password reset email directly (not queued)
            try {
                $resetUrl = route('student.password.reset', [
                    'token' => $token,
                    'email' => $student->email,
                ]);

                $emailHtml = $this->getPasswordResetEmailHtml($student, $resetUrl);

                ProfessionalMailService::send(
                    $student->email,
                    '🔐 Reset Your Password - ' . config('app.name'),
                    $emailHtml,
                    config('mail.from.address'),
                    config('mail.from.name')
                );

                \Log::info('Password reset email sent to: ' . $student->email);
            } catch (\Exception $mailError) {
                \Log::error('Failed to send password reset email: ' . $mailError->getMessage(), [
                    'email' => $student->email,
                    'error' => $mailError->getMessage(),
                    'trace' => $mailError->getTraceAsString()
                ]);

                // Delete token if sending failed
                DB::table('student_password_reset_tokens')->where('email', $request->email)->delete();

                return back()->withErrors(['email' => 'Failed to send email. Please try again later.']);
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

    /**
     * Generate HTML for password reset email (for alternative mail service)
     */
    private function getPasswordResetEmailHtml($student, $resetUrl)
    {
        $appName = config('app.name', 'Cambridge College');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">🔐 Reset Your Password</h1>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{$student->first_name}</strong>!</p>

        <p style="margin-bottom: 20px;">You are receiving this email because we received a password reset request for your account.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{$resetUrl}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; font-size: 16px;">Reset Password</a>
        </div>

        <p style="margin-bottom: 10px; color: #666; font-size: 14px;">Or copy and paste this URL into your browser:</p>
        <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 5px; font-size: 12px;">{$resetUrl}</p>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <p style="margin: 0; color: #856404; font-size: 14px;"><strong>⏰ Important:</strong> This password reset link will expire in <strong>60 minutes</strong>.</p>
        </div>

        <p style="color: #666; font-size: 14px;">If you did not request a password reset, no further action is required.</p>

        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

        <p style="color: #999; font-size: 12px; text-align: center;">
            Best Regards,<br>
            <strong>{$appName} Team</strong>
        </p>
    </div>
</body>
</html>
HTML;
    }
}
