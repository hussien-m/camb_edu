<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:1|max:100',
        ];

        // Add options validation only for multiple_choice and true_false
        if (in_array($this->question_type, ['multiple_choice', 'true_false'])) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.option_text'] = 'required|string|max:500';
            $rules['options.*.is_correct'] = 'required|in:0,1';
        }

        return $rules;
    }
    
    public function messages()
    {
        return [
            'question_text.required' => 'Question text is required.',
            'points.required' => 'Points are required.',
            'options.required' => 'At least 2 options are required.',
            'options.*.option_text.required' => 'All option fields must be filled.',
            'options.*.is_correct.required' => 'Please mark the correct answer.',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('options') && is_array($this->options)) {
                $correctCount = 0;
                foreach ($this->options as $option) {
                    if (isset($option['is_correct']) && $option['is_correct'] == 1) {
                        $correctCount++;
                    }
                }
                
                if ($correctCount === 0) {
                    $validator->errors()->add('options', 'At least one option must be marked as correct.');
                }
            }
        });
    }
}
