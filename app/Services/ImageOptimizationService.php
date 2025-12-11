<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * Optimize and save uploaded image
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Directory path in storage
     * @param int $maxWidth Maximum width
     * @param int $quality Quality (0-100)
     * @return string Saved file path
     */
    public function optimizeAndSave($file, string $path = 'courses', int $maxWidth = 800, int $quality = 80): string
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;

        // Load image
        $image = Image::read($file);

        // Resize if needed (maintain aspect ratio)
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Convert to JPEG and compress
        $encoded = $image->toJpeg($quality);

        // Save to storage
        Storage::disk('public')->put($fullPath, $encoded);

        return $fullPath;
    }

    /**
     * Create thumbnail
     */
    public function createThumbnail($file, string $path = 'thumbnails', int $width = 300, int $height = 200): string
    {
        $filename = time() . '_thumb_' . uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;

        $image = Image::read($file);
        $image->cover($width, $height);
        $encoded = $image->toJpeg(75);

        Storage::disk('public')->put($fullPath, $encoded);

        return $fullPath;
    }

    /**
     * Optimize existing image
     */
    public function optimizeExisting(string $path, int $quality = 80): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                return false;
            }

            $image = Image::read($fullPath);

            // Resize if too large
            if ($image->width() > 800) {
                $image->scale(width: 800);
            }

            // Save optimized
            $image->toJpeg($quality)->save($fullPath);

            return true;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }
}
