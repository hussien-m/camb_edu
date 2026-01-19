<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\CountryHelper;
use App\Models\Student;
use App\Services\Student\StudentEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected StudentEmailService $emailService;

    public function __construct(StudentEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function showRegistrationForm()
    {
        $countries = CountryHelper::getCountriesForSelect();
        $phoneCodes = CountryHelper::getPhoneCountryCodes();
        return view('student.auth.register', compact('countries', 'phoneCodes'));
    }

    public function register(Request $request)
    {
        $requiresVerification = (bool) config('auth.student_email_verification', true);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'country' => 'nullable|string|max:2',
            'country_code' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Get country name from code if provided
        $countryName = null;
        if ($request->country_code) {
            $countryName = CountryHelper::getCountryName($request->country_code);
        } elseif ($request->country) {
            // Fallback: if old country name is provided
            $countryName = $request->country;
        }

        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'country' => $countryName,
            'status' => $requiresVerification ? 'pending' : 'active',
            'email_verified_at' => $requiresVerification ? null : now(),
        ]);

        // Clear cache after creating new pending student
        Cache::forget('admin.pending_students');

        if ($requiresVerification) {
            // Send verification email
            $this->emailService->sendVerificationEmail($student);
        }

        // Login the student
        Auth::guard('student')->login($student);

        if ($requiresVerification) {
            return redirect()->route('student.verify.notice')
                ->with('success', 'Registration successful! Please check your email to verify your account.');
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Registration successful! Your account is active.');
    }
}
