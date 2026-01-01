<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\Student\StudentEmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendVerificationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:send-verification-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email verification reminders to unverified students (runs every 3 days)';

    protected StudentEmailService $emailService;

    /**
     * Create a new command instance.
     */
    public function __construct(StudentEmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting verification reminder process...');

        // Get unverified students registered at least 3 days ago
        $unverifiedStudents = Student::whereNull('email_verified_at')
            ->where('created_at', '<=', now()->subDays(3))
            ->get();

        if ($unverifiedStudents->isEmpty()) {
            $this->info('No unverified students found that need reminders.');
            return 0;
        }

        $this->info("Found {$unverifiedStudents->count()} unverified student(s) to send reminders to.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($unverifiedStudents as $student) {
            try {
                // Check if we should send reminder (every 3 days since registration or last reminder)
                $daysSinceRegistration = $student->created_at->diffInDays(now());
                
                // Only send if it's been 3, 6, 9, 12... days (multiples of 3)
                if ($daysSinceRegistration % 3 === 0) {
                    $this->emailService->sendVerificationReminder($student);
                    $sentCount++;
                    $this->line("✓ Reminder sent to: {$student->email} (Registered {$daysSinceRegistration} days ago)");
                } else {
                    $this->line("⊘ Skipped: {$student->email} (Not a multiple of 3 days)");
                }
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to send reminder to: {$student->email} - {$e->getMessage()}");
                Log::error("Failed to send verification reminder to {$student->email}: " . $e->getMessage());
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Total students checked: {$unverifiedStudents->count()}");
        $this->info("Reminders sent: {$sentCount}");
        $this->info("Failed: {$failedCount}");

        Log::info("Verification reminders sent", [
            'total' => $unverifiedStudents->count(),
            'sent' => $sentCount,
            'failed' => $failedCount
        ]);

        // Clean up expired tokens
        $deleted = DB::table('student_email_verification_tokens')
            ->where('expires_at', '<', now())
            ->orWhere(function($query) {
                $query->where('used', true)
                      ->where('used_at', '<', now()->subDays(7));
            })
            ->delete();

        if ($deleted > 0) {
            $this->info("Cleaned up {$deleted} expired/old verification token(s).");
        }

        return 0;
    }
}
