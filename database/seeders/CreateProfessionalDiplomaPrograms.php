<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProfessionalDiplomaPrograms extends Seeder
{
    public function run(): void
    {
        echo "Creating Professional Diploma Programs from Certificates...\n\n";

        // Get Professional Diploma level ID
        $diplomaLevelId = DB::table('course_levels')
            ->where('slug', 'professional-diploma')
            ->value('id');

        if (!$diplomaLevelId) {
            echo "ERROR: Professional Diploma level not found!\n";
            return;
        }

        echo "✓ Professional Diploma Level ID: {$diplomaLevelId}\n";

        // Get Certificate level ID
        $certificateLevelId = DB::table('course_levels')
            ->where('slug', 'certificate')
            ->value('id');

        echo "✓ Certificate Level ID: {$certificateLevelId}\n";

        // Get all Certificate courses
        $certificates = DB::table('courses')
            ->where('level_id', $certificateLevelId)
            ->where('status', 'active')
            ->get();

        echo "✓ Found {$certificates->count()} Certificate courses\n\n";

        $count = 0;
        $skipped = 0;

        foreach ($certificates as $cert) {
            // Create unique course code for diploma (PD-XXX)
            $originalCode = $cert->course_code ?? 'COURSE-' . $cert->id;
            $newCode = 'PD-' . $originalCode;

            // If too long, truncate
            if (strlen($newCode) > 50) {
                $newCode = substr($newCode, 0, 50);
            }

            // Check if already exists
            $exists = DB::table('courses')
                ->where('course_code', $newCode)
                ->exists();            if ($exists) {
                $skipped++;
                continue;
            }

            // Create new slug
            $baseSlug = $cert->slug . '-professional-diploma';
            $slug = $baseSlug;
            $counter = 1;

            while (DB::table('courses')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Enhanced description for Professional Diploma
            $enhancedDescription = $cert->description . "\n\n" .
                "<strong>Professional Diploma Program (180 Credits - 14 Months)</strong>\n" .
                "This comprehensive diploma program includes:\n" .
                "• 6 core modules covering the full professional body of knowledge\n" .
                "• 1 final project or applied practicum\n" .
                "• Blended, online, or face-to-face learning options\n" .
                "• Final examinations to ensure academic and professional competency\n\n" .
                "Graduates receive an internationally recognized diploma suitable for employment, " .
                "career progression, or further university-level study.";

            try {
                DB::table('courses')->insert([
                    'course_code' => $newCode,
                    'title' => $cert->title,
                    'slug' => $slug,
                    'category_id' => $cert->category_id,
                    'level_id' => $diplomaLevelId,
                    'short_description' => $cert->short_description,
                    'description' => $enhancedDescription,
                    'duration' => '14 months',
                    'mode' => $cert->mode ?? 'Blended Learning',
                    'fee' => $cert->fee ?? 0,
                    'image' => $cert->image,
                    'is_featured' => 0,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;

                if ($count % 10 == 0) {
                    echo "  Created {$count} programs...\n";
                }
            } catch (\Exception $e) {
                echo "ERROR creating {$newCode}: " . $e->getMessage() . "\n";
            }
        }

        echo "\n✓ Successfully created {$count} Professional Diploma programs!\n";

        if ($skipped > 0) {
            echo "⚠ Skipped {$skipped} programs (already exist)\n";
        }

        echo "\n=== Summary ===\n";
        echo "Certificate Programs: " . DB::table('courses')->where('level_id', $certificateLevelId)->count() . "\n";
        echo "Professional Diploma Programs: " . DB::table('courses')->where('level_id', $diplomaLevelId)->count() . "\n";

        $bachelorId = DB::table('course_levels')->where('slug', 'bachelors-degree')->value('id');
        $masterId = DB::table('course_levels')->where('slug', 'masters-degree')->value('id');
        $phdId = DB::table('course_levels')->where('slug', 'phd-doctoral-degree')->value('id');

        echo "Bachelor Programs: " . DB::table('courses')->where('level_id', $bachelorId)->count() . "\n";
        echo "Master Programs: " . DB::table('courses')->where('level_id', $masterId)->count() . "\n";
        echo "PhD Programs: " . DB::table('courses')->where('level_id', $phdId)->count() . "\n";
        echo "TOTAL: " . DB::table('courses')->count() . " courses\n";
        echo "=================\n";
    }
}
