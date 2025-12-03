<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SuccessStoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_published' => $this->has('is_published') ? filter_var($this->is_published, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false : false,
        ]);
    }

    public function rules()
    {
        return [
            'student_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'sometimes|boolean',
        ];
    }
}
