<?php

namespace App\Console\Commands;

use App\Models\ExamReminder;
use App\Services\Mail\ProfessionalMailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendExamReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled exam reminders to students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending exam reminders...');

        // Get reminders that are due and not sent yet
        $reminders = ExamReminder::with(['exam.course', 'student'])
            ->where('sent', false)
            ->where('scheduled_for', '<=', now())
            ->get();

        if ($reminders->isEmpty()) {
            $this->info('No pending reminders found.');
            return 0;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($reminders as $reminder) {
            try {
                // Check if exam still exists and is scheduled
                if (!$reminder->exam || !$reminder->exam->is_scheduled) {
                    $reminder->delete();
                    continue;
                }

                // Check if exam has already started or ended
                if ($reminder->exam->scheduled_start_date->isPast()) {
                    $reminder->delete();
                    continue;
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

                // Send email using ProfessionalMailService (same as password reset)
                ProfessionalMailService::send(
                    $reminder->student->email,
                    '⏰ Exam Reminder: ' . $reminder->exam->title . ' - ' . config('app.name'),
                    $emailHtml,
                    config('mail.from.address'),
                    config('mail.from.name')
                );

                // Mark as sent
                $reminder->markAsSent();

                $sentCount++;
                $this->info("✓ Sent {$reminder->reminder_type} reminder to {$reminder->student->full_name} for exam: {$reminder->exam->title}");

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to send reminder ID {$reminder->id}: {$e->getMessage()}");
                Log::error('Failed to send exam reminder', [
                    'reminder_id' => $reminder->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info("Summary:");
        $this->info("Sent: {$sentCount}");
        if ($failedCount > 0) {
            $this->error("Failed: {$failedCount}");
        }
        $this->info(str_repeat('=', 50));

        return 0;
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
}
