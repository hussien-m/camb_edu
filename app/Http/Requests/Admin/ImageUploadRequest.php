<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
