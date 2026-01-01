<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Student\StudentEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

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
     * Verify email with secure signed URL and token
     * Note: Signed URL validation is handled by 'signed' middleware
     */
    public function verify(Request $request, $id, $token = null)
    {
        // Find student
        $student = Student::find($id);

        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($student->email_verified_at) {
            // If already verified, just redirect to login
            return redirect()->route('student.login')
                ->with('info', 'Your email is already verified. Please login.');
        }

        // Verify token from database
        if (!$token) {
            return redirect()->route('student.login')
                ->with('error', 'Invalid verification link. Missing token.');
        }

        // Find token in database
        $tokenRecord = DB::table('student_email_verification_tokens')
            ->where('student_id', $student->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->get();

        $validToken = false;
        $tokenId = null;

        foreach ($tokenRecord as $record) {
            if (\Illuminate\Support\Facades\Hash::check($token, $record->token)) {
                $validToken = true;
                $tokenId = $record->id;
                break;
            }
        }

        if (!$validToken) {
            return redirect()->route('student.login')
                ->with('error', 'Invalid or expired verification token. Please request a new verification link.');
        }

        // Mark token as used
        DB::table('student_email_verification_tokens')
            ->where('id', $tokenId)
            ->update([
                'used' => true,
                'used_at' => now(),
            ]);

        // Verify and activate the student
        $student->email_verified_at = now();
        $student->status = 'active';
        $student->save();

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('admin.pending_students');
        \Illuminate\Support\Facades\Cache::forget('admin.unverified_students');

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
