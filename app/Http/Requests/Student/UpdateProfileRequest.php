<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('student')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email,' . auth('student')->id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'country' => ['nullable', 'string', 'max:100'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];

        // If new password is filled, add password validation (current_password is optional)
        if ($this->filled('new_password')) {
            $rules['current_password'] = ['nullable', 'string'];
            $rules['new_password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['new_password_confirmation'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.max' => 'Phone number must not exceed 20 characters',
            'date_of_birth.date' => 'Please enter a valid date',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'profile_photo.image' => 'Profile photo must be an image',
            'profile_photo.mimes' => 'Profile photo must be a JPEG, JPG, or PNG file',
            'profile_photo.max' => 'Profile photo must not exceed 2MB',
            'current_password.string' => 'Current password must be a valid string',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 8 characters',
            'new_password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
