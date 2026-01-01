<?php

namespace App\Services\Student;

use App\Mail\StudentVerificationMail;
use App\Mail\StudentWelcomeMail;
use App\Models\Student;
use App\Services\Mail\ProfessionalMailService;
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

        try {
            // Queue email for background sending (instant response)
            $emailHtml = $this->getVerificationEmailHtml($student, $verificationUrl);

            ProfessionalMailService::queue(
                $student->email,
                '✉️ Verify Your Email - ' . config('app.name'),
                $emailHtml,
                config('mail.from.address'),
                config('mail.from.name')
            );

            Log::info('Verification email queued for: ' . $student->email);
        } catch (\Exception $e) {
            Log::error('Failed to queue verification email: ' . $e->getMessage());
        }
    }

    /**
     * Send welcome email after verification
     */
    public function sendWelcomeEmail(Student $student): void
    {
        try {
            // Queue welcome email
            $emailHtml = $this->getWelcomeEmailHtml($student);

            ProfessionalMailService::queue(
                $student->email,
                '🎉 Welcome to ' . config('app.name'),
                $emailHtml,
                config('mail.from.address'),
                config('mail.from.name')
            );

            Log::info('Welcome email queued for: ' . $student->email);
        } catch (\Exception $e) {
            Log::error('Failed to queue welcome email: ' . $e->getMessage());
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
     * Send verification reminder email to unverified students
     */
    public function sendVerificationReminder(Student $student): void
    {
        if ($student->email_verified_at) {
            return; // Already verified, skip
        }

        $verificationUrl = $this->generateVerificationUrl($student);

        try {
            // Queue email for background sending
            $emailHtml = $this->getVerificationReminderEmailHtml($student, $verificationUrl);

            ProfessionalMailService::queue(
                $student->email,
                '🔔 Reminder: Verify Your Email - ' . config('app.name'),
                $emailHtml,
                config('mail.from.address'),
                config('mail.from.name')
            );

            Log::info('Verification reminder email queued for: ' . $student->email);
        } catch (\Exception $e) {
            Log::error('Failed to queue verification reminder email: ' . $e->getMessage());
        }
    }

    /**
     * Generate HTML for verification reminder email
     */
    private function getVerificationReminderEmailHtml($student, $verificationUrl)
    {
        $appName = config('app.name', 'Cambridge College');
        $daysSinceRegistration = $student->created_at->diffInDays(now());

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Reminder</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 0; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 700;">🔔 Email Verification Reminder</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 18px; margin-bottom: 20px; color: #333;">Hello <strong style="color: #667eea;">{$student->first_name}</strong>!</p>

                            <p style="font-size: 16px; margin-bottom: 20px; color: #555;">
                                We noticed that you haven't verified your email address yet. It's been <strong>{$daysSinceRegistration} day(s)</strong> since you registered with {$appName}.
                            </p>

                            <p style="font-size: 16px; margin-bottom: 30px; color: #555;">
                                To complete your registration and start accessing all our features, please verify your email address by clicking the button below:
                            </p>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{$verificationUrl}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 30px; display: inline-block; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">Verify Email Address Now</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top: 30px; margin-bottom: 10px; color: #666; font-size: 14px;">Or copy and paste this URL into your browser:</p>
                            <p style="word-break: break-all; background: #f8f9fa; padding: 12px; border-radius: 5px; font-size: 12px; color: #667eea; border-left: 3px solid #667eea;">{$verificationUrl}</p>

                            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 25px 0; border-radius: 5px;">
                                <p style="margin: 0; color: #856404; font-size: 14px;">
                                    <strong>⚠️ Important:</strong> Your account will remain limited until you verify your email address. Don't miss out on accessing all our courses and features!
                                </p>
                            </div>

                            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                                If you did not create an account with {$appName}, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="color: #999; font-size: 12px; margin: 0 0 10px 0;">
                                Best Regards,<br>
                                <strong style="color: #667eea;">{$appName} Team</strong>
                            </p>
                            <p style="color: #999; font-size: 11px; margin: 0;">
                                This is an automated reminder. If you have any questions, please contact our support team.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
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
