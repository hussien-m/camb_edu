<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * Upload image from editor
     */
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $path = $file->store('editor-images', 'public');
            $url = asset('storage/' . $path);

            // CKEditor 5 response format
            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
