<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateExamReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:create-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create reminders for upcoming scheduled exams';

    /**
     * Reminder intervals in minutes before exam start
     */
    private const REMINDER_INTERVALS = [
        '24h' => 1440,   // 24 hours
        '12h' => 720,    // 12 hours
        '6h' => 360,     // 6 hours
        '90min' => 90,   // 1.5 hours
        '10min' => 10,   // 10 minutes
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating reminders for upcoming scheduled exams...');

        // Get scheduled exams that start in the next 25 hours (to cover 24h reminder)
        $upcomingExams = Exam::where('is_scheduled', true)
            ->where('status', 'active')
            ->where('scheduled_start_date', '>', now())
            ->where('scheduled_start_date', '<=', now()->addHours(25))
            ->get();

        if ($upcomingExams->isEmpty()) {
            $this->info('No upcoming scheduled exams found.');
            return 0;
        }

        $createdCount = 0;

        foreach ($upcomingExams as $exam) {
            $this->info("\nProcessing exam: {$exam->title}");

            // Get enrolled students for this course
            $enrollments = Enrollment::where('course_id', $exam->course_id)
                ->where('status', 'active')
                ->with('student')
                ->get();

            foreach ($enrollments as $enrollment) {
                foreach (self::REMINDER_INTERVALS as $type => $minutesBefore) {
                    $scheduledFor = $exam->scheduled_start_date->copy()->subMinutes($minutesBefore);

                    // Only create reminder if it's in the future
                    if ($scheduledFor->isFuture()) {
                        // Check if reminder already exists
                        $exists = ExamReminder::where('exam_id', $exam->id)
                            ->where('student_id', $enrollment->student_id)
                            ->where('reminder_type', $type)
                            ->exists();

                        if (!$exists) {
                            ExamReminder::create([
                                'exam_id' => $exam->id,
                                'student_id' => $enrollment->student_id,
                                'reminder_type' => $type,
                                'scheduled_for' => $scheduledFor,
                                'sent' => false,
                            ]);

                            $createdCount++;
                            $this->info("  ✓ Created {$type} reminder for {$enrollment->student->full_name}");
                        }
                    }
                }
            }
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info("Total reminders created: {$createdCount}");
        $this->info(str_repeat('=', 50));

        return 0;
    }
}
