<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated admins can upload images
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'upload' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp', // Removed gif/svg for security
                'max:5120', // 5MB max
                'dimensions:max_width=4000,max_height=4000', // Prevent huge images
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'upload.required' => 'Image is required.',
            'upload.file' => 'File must be a valid file.',
            'upload.image' => 'File must be an image.',
            'upload.mimes' => 'Image must be jpeg, png, jpg, or webp.',
            'upload.max' => 'Image must not exceed 5MB.',
            'upload.dimensions' => 'Image dimensions must not exceed 4000x4000 pixels.',
        ];
    }
}
