<?php

namespace App\Jobs;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\Student\StudentExamService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CloseExpiredExamAttempts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(StudentExamService $examService): void
    {
        Log::info('Starting to close expired exam attempts...');

        // Find all in-progress attempts
        $inProgressAttempts = ExamAttempt::where('status', 'in_progress')
            ->with('exam')
            ->get();

        $closedCount = 0;
        $expiredCount = 0;

        foreach ($inProgressAttempts as $attempt) {
            try {
                $exam = $attempt->exam;

                // Check if exam time has expired
                $endTime = $attempt->start_time->copy()->addMinutes($exam->duration);
                $isTimeExpired = now()->gt($endTime);

                // Check if scheduled exam has ended
                $isScheduledExamEnded = false;
                if ($exam->is_scheduled && $exam->scheduled_end_date) {
                    $isScheduledExamEnded = now()->gt($exam->scheduled_end_date);
                }

                if ($isTimeExpired || $isScheduledExamEnded) {
                    // Close the attempt
                    $examService->finishAttempt($attempt, true);
                    
                    if ($isTimeExpired) {
                        $expiredCount++;
                        Log::info("Closed time-expired attempt: Attempt ID {$attempt->id}, Student ID {$attempt->student_id}, Exam ID {$exam->id}");
                    } else {
                        $closedCount++;
                        Log::info("Closed scheduled-exam-ended attempt: Attempt ID {$attempt->id}, Student ID {$attempt->student_id}, Exam ID {$exam->id}");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to close attempt {$attempt->id}: " . $e->getMessage(), [
                    'attempt_id' => $attempt->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::info("Finished closing expired attempts. Time-expired: {$expiredCount}, Scheduled-ended: {$closedCount}");
    }
}
