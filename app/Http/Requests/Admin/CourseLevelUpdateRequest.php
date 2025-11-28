<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseLevelUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $levelId = $this->route('level');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:course_levels,slug,' . $levelId,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
