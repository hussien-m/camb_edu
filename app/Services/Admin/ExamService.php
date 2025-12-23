<?php

namespace App\Services\Admin;

use App\Models\Exam;
use App\Models\Enrollment;
use App\Models\ExamReminder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamService
{
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
     * Create a new exam.
     */
    public function createExam(array $data): Exam
    {
        DB::beginTransaction();
        try {
            $exam = Exam::create($data);

            // Create reminders if exam is scheduled
            if ($exam->is_scheduled && $exam->scheduled_start_date) {
                $this->createRemindersForExam($exam);
            }

            DB::commit();
            return $exam;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating exam: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing exam.
     */
    public function updateExam(Exam $exam, array $data): bool
    {
        DB::beginTransaction();
        try {
            $wasScheduled = $exam->is_scheduled;
            $oldStartDate = $exam->scheduled_start_date;

            $updated = $exam->update($data);

            // Handle reminders
            if ($exam->is_scheduled && $exam->scheduled_start_date) {
                // If scheduling changed, recreate reminders
                if (!$wasScheduled || $oldStartDate != $exam->scheduled_start_date) {
                    // Delete old reminders that haven't been sent
                    ExamReminder::where('exam_id', $exam->id)
                        ->where('sent', false)
                        ->delete();

                    // Create new reminders
                    $this->createRemindersForExam($exam);
                }
            } else {
                // If scheduling was disabled, delete all unsent reminders
                ExamReminder::where('exam_id', $exam->id)
                    ->where('sent', false)
                    ->delete();
            }

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating exam: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an exam.
     */
    public function deleteExam(Exam $exam): bool
    {
        return $exam->delete();
    }

    /**
     * Create reminders for a scheduled exam
     */
    private function createRemindersForExam(Exam $exam): void
    {
        // Get enrolled students for this course
        $enrollments = Enrollment::where('course_id', $exam->course_id)
            ->where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            foreach (self::REMINDER_INTERVALS as $type => $minutesBefore) {
                $scheduledFor = $exam->scheduled_start_date->copy()->subMinutes($minutesBefore);

                // Only create reminder if it's in the future
                if ($scheduledFor->isFuture()) {
                    ExamReminder::create([
                        'exam_id' => $exam->id,
                        'student_id' => $enrollment->student_id,
                        'reminder_type' => $type,
                        'scheduled_for' => $scheduledFor,
                        'sent' => false,
                    ]);
                }
            }
        }

        Log::info("Created reminders for exam: {$exam->title} (ID: {$exam->id})");
    }
}
