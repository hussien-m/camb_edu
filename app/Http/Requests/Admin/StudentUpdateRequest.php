<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $studentId = $this->route('student');

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,pending',
            'date_of_birth' => 'nullable|date',
            'country' => 'nullable|string|max:255',
        ];
    }
}
