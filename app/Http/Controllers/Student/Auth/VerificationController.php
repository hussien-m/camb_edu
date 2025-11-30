<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Student\StudentEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    protected StudentEmailService $emailService;

    public function __construct(StudentEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show verification notice page
     */
    public function notice()
    {
        $student = Auth::guard('student')->user();

        if ($student && $student->email_verified_at) {
            return redirect()->route('student.dashboard');
        }

        return view('student.auth.verify');
    }

    /**
     * Verify email with URL - THIS IS THE FIX
     */
    public function verify(Request $request, $id, $hash)
    {
        // Find student
        $student = Student::find($id);

        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Invalid verification link.');
        }

        // Check if hash matches
        if ($hash !== sha1($student->email)) {
            return redirect()->route('student.login')
                ->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($student->email_verified_at) {
            // Login and go to dashboard
            Auth::guard('student')->login($student);
            return redirect()->route('student.dashboard')
                ->with('info', 'Your email is already verified.');
        }

        // ACTIVATE THE STUDENT NOW!
        $student->email_verified_at = now();
        $student->status = 'active';
        $student->save();

        // Send welcome email
        try {
            $this->emailService->sendWelcomeEmail($student);
        } catch (\Exception $e) {
            // Ignore email errors
        }

        // Login the student
        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard')
            ->with('success', '🎉 Your email has been verified successfully! Welcome!');
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student.login');
        }

        if ($student->email_verified_at) {
            return redirect()->route('student.dashboard')
                ->with('info', 'Your email is already verified.');
        }

        $this->emailService->sendVerificationEmail($student);

        return back()->with('success', 'A new verification link has been sent to your email.');
    }
}
