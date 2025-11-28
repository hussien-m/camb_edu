<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'upload.required' => 'Image is required.',
            'upload.image' => 'File must be an image.',
            'upload.mimes' => 'Image must be jpeg, png, jpg, or gif.',
            'upload.max' => 'Image must not exceed 2MB.',
        ];
    }
}
