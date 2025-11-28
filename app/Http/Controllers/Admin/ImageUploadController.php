<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadImageRequest;
use App\Services\Admin\ImageUploadService;

class ImageUploadController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function upload(UploadImageRequest $request)
    {
        $result = $this->imageService->uploadImage($request->file('upload'));

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        // CKEditor 5 response format
        return response()->json([
            'url' => $result['url']
        ]);
    }
}
