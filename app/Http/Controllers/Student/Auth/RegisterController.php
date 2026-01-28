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
        $countries  = CountryHelper::getCountriesForSelect();
        $phoneCodes = CountryHelper::getPhoneCountryCodes();

        return view('student.auth.register', compact('countries', 'phoneCodes'));
    }

    public function register(Request $request)
    {
        $requiresVerification = (bool) config('auth.student_email_verification', true);

        $validator = Validator::make($request->all(), [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:20',
                function ($attr, $value, $fail) {
                    $this->validateSingleName($value, $fail);
                }
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:20',
                function ($attr, $value, $fail) {
                    $this->validateSingleName($value, $fail);
                }
            ],
            'email' => 'required|string|email|max:255|unique:students,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'country' => 'nullable|string|max:2',
            'country_code' => 'nullable|string|max:2',

            // Honeypot
            'company' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Honeypot → bot
        if ($request->filled('company')) {
            abort(403);
        }

        // Resolve country
        $countryName = null;
        if ($request->country_code) {
            $countryName = CountryHelper::getCountryName($request->country_code);
        } elseif ($request->country) {
            $countryName = $request->country;
        }

        // Soft suspicious flag
        $isSuspicious =
            mb_strlen($request->first_name) > 15 ||
            mb_strlen($request->last_name) > 15;

        $status = $isSuspicious
            ? 'suspicious'
            : ($requiresVerification ? 'pending' : 'active');

        $student = Student::create([
            'first_name'        => $this->normalizeName($request->first_name),
            'last_name'         => $this->normalizeName($request->last_name),
            'email'             => strtolower(trim($request->email)),
            'password'          => Hash::make($request->password),
            'phone'             => $request->phone,
            'date_of_birth'     => $request->date_of_birth,
            'country'           => $countryName,
            'status'            => $status,
            'email_verified_at' => $requiresVerification ? null : now(),
        ]);

        Cache::forget('admin.pending_students');

        if ($requiresVerification && $status !== 'suspicious') {
            $this->emailService->sendVerificationEmail($student);
        }

        Auth::guard('student')->login($student);

        if ($status === 'suspicious') {
            return redirect()
                ->route('student.profile.edit')
                ->with('warning', 'Please update your real name to activate your account.');
        }

        if ($requiresVerification) {
            return redirect()
                ->route('student.verify.notice')
                ->with('success', 'Registration successful! Please verify your email.');
        }

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Registration successful!');
    }

    /**
     * Normalize single name
     */
    private function normalizeName($name): string
    {
        return trim((string) $name);
    }

    /**
     * Validate ONE name token (First OR Last)
     */
    private function validateSingleName($name, $fail): void
    {
        $name = trim((string) $name);

        // حروف فقط (أي لغة) + فواصل بسيطة
        if (!preg_match("/^[\p{L}\p{M}\.\-']+$/u", $name)) {
            $fail('Invalid name.');
            return;
        }

        // تكرار نفس الحرف (aaaa / لللل)
        if (preg_match('/(.)\1{2,}/u', $name)) {
            $fail('Invalid name.');
            return;
        }

        // طول منطقي
        $len = mb_strlen($name);
        if ($len < 2 || $len > 20) {
            $fail('Invalid name.');
            return;
        }

        // English garbage (asdasdsa / qwrty)
        if (preg_match('/^[a-zA-Z]+$/', $name)) {
            if (!preg_match('/[aeiouAEIOU]/', $name)) {
                $fail('Invalid name.');
                return;
            }
        }

        // keyboard patterns
        if (preg_match('/^(asd|qwe|zxc|qaz|wsx|edc|rfv|tgb|yhn|ujm)+/i', $name)) {
            $fail('Invalid name.');
            return;
        }
    }
}
