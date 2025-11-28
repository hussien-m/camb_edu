<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExamResultUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'score' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'passed' => 'required|boolean',
            'admin_notes' => 'nullable|string',
        ];
    }
}
