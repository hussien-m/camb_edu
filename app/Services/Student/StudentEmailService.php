<?php

namespace App\Services\Student;

use App\Mail\StudentVerificationMail;
use App\Mail\StudentWelcomeMail;
use App\Models\Student;
use App\Services\Mail\AlternativeMailService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class StudentEmailService
{
    /**
     * Send verification email to student
     */
    public function sendVerificationEmail(Student $student): void
    {
        $verificationUrl = $this->generateVerificationUrl($student);

        @ini_set('default_socket_timeout', 10);
        @set_time_limit(20);

        try {
            // Try SMTP first
            Mail::to($student->email)->send(
                new StudentVerificationMail($student, $verificationUrl)
            );
            Log::info('Verification email sent via SMTP to: ' . $student->email);
        } catch (\Exception $e) {
            Log::warning('SMTP failed, trying alternative method: ' . $e->getMessage());

            // Fallback to alternative mail
            try {
                $emailHtml = $this->getVerificationEmailHtml($student, $verificationUrl);
                AlternativeMailService::sendWithFallback(
                    $student->email,
                    '✉️ Verify Your Email - ' . config('app.name'),
                    $emailHtml,
                    config('mail.from.address'),
                    config('mail.from.name')
                );
                Log::info('Verification email sent via alternative method');
            } catch (\Exception $altError) {
                Log::error('All email methods failed: ' . $altError->getMessage());
                // Don't throw - let registration continue
            }
        }
    }

    /**
     * Send welcome email after verification
     */
    public function sendWelcomeEmail(Student $student): void
    {
        // Use SMTP only for reliable delivery
        @ini_set('default_socket_timeout', 10);
        @set_time_limit(20);

        try {
            Mail::to($student->email)->send(
                new StudentWelcomeMail($student)
            );
            Log::info('Welcome email sent via SMTP to: ' . $student->email);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
            // Don't throw - welcome email is not critical
        }
    }

    /**
     * Generate signed verification URL
     */
    private function generateVerificationUrl(Student $student): string
    {
        return route('student.verify.email', [
            'id' => $student->id,
            'hash' => sha1($student->email),
        ]);
    }

    /**
     * Verify student email
     */
    public function verifyEmail(Student $student): bool
    {
        if ($student->email_verified_at) {
            return false; // Already verified
        }

        $wasPending = $student->status === 'pending';
        $student->update([
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Clear cache if student was pending
        if ($wasPending) {
            Cache::forget('admin.pending_students');
        }

        // Send welcome email
        $this->sendWelcomeEmail($student);

        return true;
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Student $student): bool
    {
        if ($student->email_verified_at) {
            return false; // Already verified
        }

        $this->sendVerificationEmail($student);
        return true;
    }

    /**
     * Generate HTML for verification email (for alternative mail service)
     */
    private function getVerificationEmailHtml($student, $verificationUrl)
    {
        $appName = config('app.name', 'Cambridge College');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">✉️ Verify Your Email</h1>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{$student->first_name}</strong>!</p>

        <p style="margin-bottom: 20px;">Thank you for registering with {$appName}! Please verify your email address to activate your account.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{$verificationUrl}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; font-size: 16px;">Verify Email Address</a>
        </div>

        <p style="margin-bottom: 10px; color: #666; font-size: 14px;">Or copy and paste this URL into your browser:</p>
        <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 5px; font-size: 12px;">{$verificationUrl}</p>

        <p style="color: #666; font-size: 14px; margin-top: 20px;">If you did not create an account, no further action is required.</p>

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

    /**
     * Generate HTML for welcome email (for alternative mail service)
     */
    private function getWelcomeEmailHtml($student)
    {
        $appName = config('app.name', 'Cambridge College');
        $dashboardUrl = route('student.dashboard');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">🎉 Welcome to {$appName}!</h1>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{$student->first_name}</strong>!</p>

        <p style="margin-bottom: 20px;">Your email has been verified successfully! Welcome to {$appName}.</p>

        <p style="margin-bottom: 20px;">You can now access your dashboard and start exploring our courses.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{$dashboardUrl}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; font-size: 16px;">Go to Dashboard</a>
        </div>

        <p style="color: #666; font-size: 14px; margin-top: 20px;">If you have any questions, feel free to contact our support team.</p>

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
