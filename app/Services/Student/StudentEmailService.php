<?php

namespace App\Services\Student;

use App\Mail\StudentVerificationMail;
use App\Mail\StudentWelcomeMail;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class StudentEmailService
{
    /**
     * Send verification email to student
     */
    public function sendVerificationEmail(Student $student): void
    {
        $verificationUrl = $this->generateVerificationUrl($student);

        Mail::to($student->email)->send(
            new StudentVerificationMail($student, $verificationUrl)
        );
    }

    /**
     * Send welcome email after verification
     */
    public function sendWelcomeEmail(Student $student): void
    {
        Mail::to($student->email)->send(
            new StudentWelcomeMail($student)
        );
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

        $student->update([
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

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
}
