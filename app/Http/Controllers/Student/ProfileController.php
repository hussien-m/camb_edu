<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateProfileRequest;
use App\Services\Student\StudentProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(StudentProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit()
    {
        $student = auth()->guard('student')->user();
        $countries = \App\Helpers\CountryHelper::getAllCountries();
        return view('student.profile', compact('student', 'countries'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $student = auth()->guard('student')->user();

        $result = $this->profileService->updateProfile($student, $request->validated());

        if (!$result['success']) {
            return back()->withErrors(['current_password' => $result['message']])->withInput();
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
