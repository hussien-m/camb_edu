<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $categoryId = $this->route('category');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:course_categories,slug,' . $categoryId,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ];
    }
}
