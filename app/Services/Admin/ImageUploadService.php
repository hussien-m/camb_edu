<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Upload image from editor.
     */
    public function uploadImage($file)
    {
        $path = $file->store('editor-images', 'public');
        return asset('storage/' . $path);
    }
}
