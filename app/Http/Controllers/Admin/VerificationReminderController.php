<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Student\StudentEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationReminderController extends Controller
{
    protected StudentEmailService $emailService;

    public function __construct(StudentEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show the verification reminder page
     */
    public function index()
    {
        // Get unverified students
        $unverifiedStudents = Student::whereNull('email_verified_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics
        $totalUnverified = Student::whereNull('email_verified_at')->count();
        $unverifiedLast3Days = Student::whereNull('email_verified_at')
            ->where('created_at', '>=', now()->subDays(3))
            ->count();
        $unverifiedLast7Days = Student::whereNull('email_verified_at')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.verification-reminders.index', compact(
            'unverifiedStudents',
            'totalUnverified',
            'unverifiedLast3Days',
            'unverifiedLast7Days'
        ));
    }

    /**
     * Send reminder to all unverified students
     */
    public function sendToAll(Request $request)
    {
        $unverifiedStudents = Student::whereNull('email_verified_at')->get();

        if ($unverifiedStudents->isEmpty()) {
            return back()->with('info', 'No unverified students found.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($unverifiedStudents as $student) {
            try {
                $this->emailService->sendVerificationReminder($student);
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to send verification reminder to {$student->email}: " . $e->getMessage());
            }
        }

        $message = "Reminders sent successfully! Sent: {$sentCount}";
        if ($failedCount > 0) {
            $message .= ", Failed: {$failedCount}";
        }

        return back()->with('success', $message);
    }

    /**
     * Send reminder to specific student
     */
    public function sendToStudent(Student $student)
    {
        if ($student->email_verified_at) {
            return back()->with('error', 'This student has already verified their email.');
        }

        try {
            $this->emailService->sendVerificationReminder($student);
            return back()->with('success', "Verification reminder sent successfully to {$student->email}.");
        } catch (\Exception $e) {
            Log::error("Failed to send verification reminder to {$student->email}: " . $e->getMessage());
            return back()->with('error', 'Failed to send reminder. Please try again.');
        }
    }

    /**
     * Send reminder to students registered in last N days
     */
    public function sendToRecent(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:30'
        ]);

        $days = $request->input('days');
        $unverifiedStudents = Student::whereNull('email_verified_at')
            ->where('created_at', '>=', now()->subDays($days))
            ->get();

        if ($unverifiedStudents->isEmpty()) {
            return back()->with('info', "No unverified students found registered in the last {$days} day(s).");
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($unverifiedStudents as $student) {
            try {
                $this->emailService->sendVerificationReminder($student);
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to send verification reminder to {$student->email}: " . $e->getMessage());
            }
        }

        $message = "Reminders sent to {$sentCount} student(s) registered in the last {$days} day(s).";
        if ($failedCount > 0) {
            $message .= " Failed: {$failedCount}";
        }

        return back()->with('success', $message);
    }
}
