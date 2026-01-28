<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'max_attempts' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'is_scheduled' => 'nullable|boolean',
            'scheduled_start_date' => 'nullable|required_if:is_scheduled,1|date|after:now',
            'scheduled_end_date' => 'nullable|date|required_with:scheduled_start_date|after:scheduled_start_date',
            'timezone' => 'nullable|string|timezone',
            'scheduling_notes' => 'nullable|string|max:1000',
            'group_assignment_enabled' => 'nullable|boolean',
            'allow_enrolled_access' => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_scheduled' => $this->has('is_scheduled') ? true : false,
            'group_assignment_enabled' => $this->has('group_assignment_enabled') ? true : false,
            'allow_enrolled_access' => $this->has('allow_enrolled_access') ? true : false,
        ]);
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages()
    {
        return [
            'scheduled_start_date.required_if' => 'Start date is required when scheduling is enabled.',
            'scheduled_start_date.after' => 'Start date must be in the future.',
            'scheduled_end_date.after' => 'End date must be after the start date.',
            'timezone.timezone' => 'Please select a valid timezone.',
        ];
    }
}
