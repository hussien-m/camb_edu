<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false : false,
            'open_in_new_tab' => $this->has('open_in_new_tab') ? filter_var($this->open_in_new_tab, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:banner,sidebar,inline,popup',
            'position' => 'required|in:top,middle,bottom,sidebar-left,sidebar-right',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'html_content' => 'nullable|string',
            'link' => 'nullable|url|max:500',
            'open_in_new_tab' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }
}
