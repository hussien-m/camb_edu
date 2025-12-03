<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    /**
     * Allowed MIME types for security
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ];

    /**
     * Allowed extensions for security
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Max file size in bytes (5MB)
     */
    private const MAX_FILE_SIZE = 5242880; // 5MB

    /**
     * Upload image from editor with security checks.
     */
    public function uploadImage($file)
    {
        try {
            // 1. Validate file exists and is valid
            if (!$file || !$file->isValid()) {
                return [
                    'success' => false,
                    'message' => 'Invalid file uploaded.'
                ];
            }

            // 2. Check file size
            if ($file->getSize() > self::MAX_FILE_SIZE) {
                return [
                    'success' => false,
                    'message' => 'File size exceeds maximum allowed size of 5MB.'
                ];
            }

            // 3. Validate MIME type (server-side check)
            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                Log::warning('Blocked file upload - Invalid MIME type', [
                    'mime_type' => $mimeType,
                    'original_name' => $file->getClientOriginalName(),
                    'ip' => request()->ip()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Invalid file type. Only images are allowed.'
                ];
            }

            // 4. Validate extension
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                return [
                    'success' => false,
                    'message' => 'Invalid file extension.'
                ];
            }

            // 5. Generate secure random filename (prevents overwriting)
            $filename = Str::random(40) . '_' . time() . '.' . $extension;
            
            // 6. Sanitize filename (remove any special characters)
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

            // 7. Store file in organized directory structure (by month)
            $directory = 'editor-images/' . date('Y') . '/' . date('m');
            $path = $file->storeAs($directory, $filename, 'public');
            
            if (!$path) {
                return [
                    'success' => false,
                    'message' => 'Failed to store image.'
                ];
            }

            // 8. Log successful upload for security audit
            Log::info('Image uploaded successfully', [
                'filename' => $filename,
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $mimeType,
                'admin_id' => auth()->guard('admin')->id(),
                'ip' => request()->ip()
            ]);

            // 9. Return success with URL
            return [
                'success' => true,
                'url' => asset('storage/' . $path),
                'path' => $path
            ];

        } catch (\Exception $e) {
            Log::error('Image upload failed with exception', [
                'file' => $file ? $file->getClientOriginalName() : 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->guard('admin')->id(),
                'ip' => request()->ip()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while uploading the image.'
            ];
        }
    }
}
