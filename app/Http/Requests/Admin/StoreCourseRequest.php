<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_featured' => $this->has('is_featured') ? filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false : false,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug',
            'category_id' => 'nullable|exists:course_categories,id',
            'level_id' => 'nullable|exists:course_levels,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'mode' => 'nullable|string|max:50',
            'fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'status.required' => 'Course status is required.',
        ];
    }
}
