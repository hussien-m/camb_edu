<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
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
            'scheduled_start_date' => 'nullable|required_if:is_scheduled,1|date',
            'scheduled_end_date' => 'nullable|date|after:scheduled_start_date',
            'timezone' => 'nullable|string|timezone',
            'scheduling_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_scheduled' => $this->has('is_scheduled') ? true : false,
        ]);
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages()
    {
        return [
            'scheduled_start_date.required_if' => 'Start date is required when scheduling is enabled.',
            'scheduled_end_date.after' => 'End date must be after the start date.',
            'timezone.timezone' => 'Please select a valid timezone.',
        ];
    }
}
