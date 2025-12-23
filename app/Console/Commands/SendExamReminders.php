<?php

namespace App\Console\Commands;

use App\Models\ExamReminder;
use App\Notifications\ExamReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

                // Send notification
                $reminder->student->notify(
                    new ExamReminderNotification(
                        $reminder->exam,
                        $reminder->reminder_type,
                        $timeRemaining
                    )
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
}
