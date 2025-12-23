<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamReminder;
use App\Services\Mail\ProfessionalMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamReminderController extends Controller
{
    /**
     * Show reminders dashboard
     */
    public function index()
    {
        $stats = [
            'total' => ExamReminder::count(),
            'pending' => ExamReminder::where('sent', 0)->count(),
            'sent' => ExamReminder::where('sent', 1)->count(),
            'due' => ExamReminder::where('sent', 0)
                ->where('scheduled_for', '<=', now())
                ->count(),
        ];

        $dueReminders = ExamReminder::with(['exam', 'student'])
            ->where('sent', 0)
            ->where('scheduled_for', '<=', now())
            ->orderBy('scheduled_for')
            ->limit(20)
            ->get();

        $upcomingReminders = ExamReminder::with(['exam', 'student'])
            ->where('sent', 0)
            ->where('scheduled_for', '>', now())
            ->orderBy('scheduled_for')
            ->limit(20)
            ->get();

        $recentSent = ExamReminder::with(['exam', 'student'])
            ->where('sent', 1)
            ->orderBy('sent_at', 'desc')
            ->limit(10)
            ->get();

        // Time diagnostics
        $timeDiagnostics = [
            'server_time' => now()->format('Y-m-d H:i:s T'),
            'laravel_timezone' => config('app.timezone'),
            'db_time' => DB::select('SELECT NOW() as time')[0]->time,
            'php_timezone' => date_default_timezone_get(),
        ];

        return view('admin.exam-reminders.index', compact(
            'stats',
            'dueReminders',
            'upcomingReminders',
            'recentSent',
            'timeDiagnostics'
        ));
    }

    /**
     * Create reminders for scheduled exams
     */
    public function create()
    {
        try {
            Artisan::call('exams:create-reminders');
            $output = Artisan::output();

            return back()->with('success', 'Reminders created successfully! ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create reminders: ' . $e->getMessage());
        }
    }

    /**
     * Send due reminders
     */
    public function send()
    {
        try {
            Artisan::call('exams:send-reminders');
            $output = Artisan::output();

            return back()->with('success', 'Reminders sent successfully! ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reminders: ' . $e->getMessage());
        }
    }

    /**
     * Delete all unsent reminders
     */
    public function deleteUnsent()
    {
        try {
            $count = ExamReminder::where('sent', 0)->delete();
            return back()->with('success', "Deleted {$count} unsent reminders.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete reminders: ' . $e->getMessage());
        }
    }

    /**
     * Test email sending
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $html = $this->getTestEmailHtml();

            ProfessionalMailService::send(
                $request->email,
                '✉️ Test Email - Exam Reminders System',
                $html,
                config('mail.from.address'),
                config('mail.from.name')
            );

            return back()->with('success', 'Test email sent to ' . $request->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Send a specific reminder immediately
     */
    public function sendReminder($id)
    {
        try {
            $reminder = ExamReminder::with(['exam', 'student'])->findOrFail($id);

            if ($reminder->sent) {
                return back()->with('error', 'This reminder has already been sent.');
            }

            // Get time remaining text
            $timeRemaining = $this->getTimeRemainingText($reminder->reminder_type);

            // Generate email HTML
            $emailHtml = $this->getExamReminderEmailHtml(
                $reminder->student,
                $reminder->exam,
                $reminder->reminder_type,
                $timeRemaining
            );

            // Send email using ProfessionalMailService
            ProfessionalMailService::send(
                $reminder->student->email,
                '⏰ Exam Reminder: ' . $reminder->exam->title . ' - ' . config('app.name'),
                $emailHtml,
                config('mail.from.address'),
                config('mail.from.name')
            );

            // Mark as sent
            $reminder->markAsSent();

            return back()->with('success', "Reminder sent to {$reminder->student->email} for exam: {$reminder->exam->title}");
        } catch (\Exception $e) {
            \Log::error('Failed to send manual reminder', [
                'reminder_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    /**
     * Get human-readable time remaining text
     */
    private function getTimeRemainingText(string $reminderType): string
    {
        return match($reminderType) {
            '24h' => '24 hours',
            '12h' => '12 hours',
            '6h' => '6 hours',
            '90min' => '1 hour and 30 minutes',
            '10min' => '10 minutes',
            default => $reminderType,
        };
    }

    /**
     * Generate beautiful HTML email for exam reminder
     */
    private function getExamReminderEmailHtml($student, $exam, $reminderType, $timeRemaining)
    {
        $appName = config('app.name', 'Cambridge College');
        $examUrl = route('student.exams.show', $exam->id);
        $startDate = Carbon::parse($exam->scheduled_start_date)->format('l, F j, Y');
        $startTime = Carbon::parse($exam->scheduled_start_date)->format('h:i A');
        $timezone = $exam->timezone ?? config('app.timezone');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">⏰ Exam Reminder</h1>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">
            Hello <strong>{$student->first_name} {$student->last_name}</strong>,
        </p>

        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; margin-bottom: 20px;">
            <h2 style="color: #667eea; margin-top: 0; font-size: 20px;">📝 {$exam->title}</h2>
            <p style="font-size: 18px; color: #e74c3c; font-weight: bold; margin: 15px 0;">
                ⏱️ Starting in: {$timeRemaining}
            </p>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="color: #555; font-size: 16px; margin-top: 0;">📅 Exam Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #666; font-weight: bold;">📆 Date:</td>
                    <td style="padding: 8px 0; color: #333;">{$startDate}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-weight: bold;">🕐 Time:</td>
                    <td style="padding: 8px 0; color: #333;">{$startTime} ({$timezone})</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-weight: bold;">⏲️ Duration:</td>
                    <td style="padding: 8px 0; color: #333;">{$exam->duration_minutes} minutes</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-weight: bold;">💯 Total Marks:</td>
                    <td style="padding: 8px 0; color: #333;">{$exam->total_marks}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666; font-weight: bold;">✅ Passing Score:</td>
                    <td style="padding: 8px 0; color: #333;">{$exam->passing_marks}%</td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{$examUrl}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                View Exam Details
            </a>
        </div>

        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <p style="margin: 0; color: #856404; font-size: 14px;">
                <strong>⚠️ Important:</strong> Make sure you're ready at the scheduled time. Late entries may not be allowed.
            </p>
        </div>

        <p style="margin-top: 30px; color: #666; font-size: 14px; text-align: center;">
            Good luck with your exam! 🍀
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #999; font-size: 12px; text-align: center; margin: 0;">
            This is an automated reminder from {$appName}<br>
            If you have any questions, please contact your administrator.
        </p>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate test email HTML
     */
    private function getTestEmailHtml()
    {
        $appName = config('app.name', 'Cambridge College');
        $time = now()->format('l, F j, Y h:i:s A T');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">✉️ Test Email</h1>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px;">
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2 style="color: #28a745; margin-top: 0;">Email System Working! ✅</h2>
            <p>This is a test email from the Exam Reminder System.</p>
            <p><strong>Sent at:</strong> {$time}</p>
        </div>

        <div style="background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px;">
            <p style="margin: 0; color: #0c5460; font-size: 14px;">
                <strong>ℹ️ Note:</strong> If you received this email, your email system is configured correctly!
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #999; font-size: 12px; text-align: center; margin: 0;">
            {$appName} - Exam Reminder System
        </p>
    </div>
</body>
</html>
HTML;
    }
}
