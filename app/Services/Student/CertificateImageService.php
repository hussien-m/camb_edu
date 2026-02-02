<?php

namespace App\Services\Student;

use App\Models\Certificate;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CertificateImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Generate certificate PNG with same content and order as the displayed template.
     * Uses Settings (site_name, certificate_tagline) so download matches the page.
     */
    public function generatePng(Certificate $certificate): string
    {
        $width = config('certificate.image_width', 1200);
        $height = config('certificate.image_height', 1697);
        $fontPath = config('certificate.font_path');
        $useTtf = $fontPath && is_string($fontPath) && file_exists($fontPath);

        // Same source as template: Settings table
        $centerName = setting('site_name', config('app.name'));
        $centerTagline = setting('certificate_tagline', setting('site_title', 'Authorized Training Center'));

        $studentName = $certificate->student->full_name ?? $certificate->student->name ?? 'Student';
        $courseTitle = $certificate->display_course_title;
        $certNumber = $certificate->certificate_number;
        $issueDate = $certificate->issue_date->format('F d, Y');

        $image = $this->manager->create($width, $height);
        $this->fillBackground($image);

        $cx = (int) ($width / 2);

        $font = function ($path, $size, $color) use ($useTtf) {
            return function ($font) use ($path, $size, $color, $useTtf) {
                if ($useTtf && $path && file_exists($path)) {
                    $font->filename($path);
                }
                $font->size($size);
                $font->color($color);
                $font->align('center');
                $font->valign('bottom');
            };
        };

        // Same content & order as template.blade.php
        // 1. Center branding
        $this->drawText($image, $centerName, $cx, 140, $font($fontPath, 32, '#1a1a2e'));
        $this->drawText($image, $centerTagline, $cx, 175, $font($fontPath, 14, '#5c5c6b'));

        // 2. Certificate type (same labels as template)
        $this->drawText($image, 'Professional Certificate', $cx, 230, $font($fontPath, 12, '#6b6b7b'));
        $this->drawText($image, 'Certificate of Completion', $cx, 265, $font($fontPath, 28, '#1a1a2e'));

        // 3. Statement & recipient (same wording as template)
        $this->drawText($image, 'This is to certify that', $cx, 320, $font($fontPath, 18, '#3d3d4a'));
        $this->drawText($image, $studentName, $cx, 375, $font($fontPath, 38, '#8b6914'));
        $this->drawText($image, 'has successfully completed the accredited program and demonstrated the required competencies in', $cx, 450, $font($fontPath, 18, '#3d3d4a'));
        $this->drawText($image, $courseTitle, $cx, 520, $font($fontPath, 24, '#1a1a2e'));

        // 4. Meta (same labels as template)
        $this->drawText($image, 'Certificate No.', (int) ($width * 0.25), 1280, $font($fontPath, 12, '#6b6b7b'));
        $this->drawText($image, $certNumber, (int) ($width * 0.25), 1310, $font($fontPath, 16, '#1a1a2e'));
        $this->drawText($image, 'Date of Issue', (int) ($width * 0.75), 1280, $font($fontPath, 12, '#6b6b7b'));
        $this->drawText($image, $issueDate, (int) ($width * 0.75), 1310, $font($fontPath, 16, '#1a1a2e'));

        // 5. Seal (same as template: Official / Verified)
        $this->drawText($image, 'Official', $cx, 1405, $font($fontPath, 14, '#8b6914'));
        $this->drawText($image, 'Verified', $cx, 1430, $font($fontPath, 12, '#5c5c6b'));

        // 6. Footer (same as template)
        $this->drawText($image, $centerName . ' • ' . $centerTagline, $cx, 1540, $font($fontPath, 12, '#6b6b7b'));

        return $image->toPng()->toString();
    }

    protected function fillBackground($image): void
    {
        $image->fill('#f5f3ef');
    }

    protected function drawText($image, string $text, int $x, int $y, callable $fontCallback): void
    {
        $image->text($text, $x, $y, $fontCallback);
    }
}
