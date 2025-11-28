<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCambridgeCourses extends Seeder
{
    public function run(): void
    {
        echo "Starting Cambridge Courses Import with REAL descriptions...\n\n";

        // Get category and level IDs
        $categories = DB::table('course_categories')->pluck('id', 'slug')->toArray();
        $levels = DB::table('course_levels')->pluck('id', 'slug')->toArray();

        echo "✓ Category and Level IDs loaded\n";

        // Read the markdown file
        $filePath = base_path('Certificate Programs.md');
        if (!file_exists($filePath)) {
            echo "ERROR: Certificate Programs.md not found!\n";
            return;
        }

        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);

        // Parse Certificate Programs (150)
        echo "\n[1/4] Importing Certificate Programs with real descriptions...\n";

        $certificateCount = 0;
        foreach ($lines as $line) {
            // Match lines like: "1	EDU101	Education	Classroom Management Strategies	Techniques to..."
            if (preg_match('/^\d+\t([A-Z&;]+\d+)\t([^\t]+)\t([^\t]+)\t(.+)$/', $line, $matches)) {
                $code = $matches[1];
                $categoryName = $matches[2];
                $title = $matches[3];
                $description = $matches[4];

                // Map category name to slug
                $categorySlug = $this->getCategorySlug($categoryName);
                if (!isset($categories[$categorySlug])) {
                    continue;
                }

                DB::table('courses')->insert([
                    'course_code' => $code,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'short_description' => $description,
                    'description' => $description . ' This professional certificate from Cambridge British International College includes 20 credits delivered through flexible blended learning.',
                    'category_id' => $categories[$categorySlug],
                    'level_id' => $levels['certificate'],
                    'duration' => '3-6 months',
                    'mode' => 'Blended',
                    'fee' => 0,
                    'status' => 'active',
                    'is_featured' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $certificateCount++;
            }
        }

        echo "✓ Imported $certificateCount Certificate programs\n";

        // Parse Bachelor's Degrees
        echo "\n[2/4] Importing Bachelor's Degree Programs...\n";

        $bachelorCount = 0;
        $inBachelorSection = false;

        foreach ($lines as $line) {
            if (strpos($line, "Education (1–50)") !== false ||
                strpos($line, "Educational Leadership") !== false && strpos($line, "(51–90)") !== false) {
                $inBachelorSection = true;
                continue;
            }

            // Match "1. Bachelor ... – description" or just "1. Bachelor ..."
            if ($inBachelorSection) {
                $matched = false;
                $title = '';
                $description = '';

                // Try with description first
                if (preg_match('/^(\d+)[\.\t\s]+(.+?)\s*[–—-]\s*(.+)$/u', $line, $matches)) {
                    $title = trim($matches[2]);
                    $description = trim($matches[3]);
                    $matched = true;
                }
                // Try without description - match full Bachelor title
                elseif (preg_match('/^(\d+)[\.\t\s]+(Bachelor(?:\s+of)?[\s\w&\',()–-]+?)$/u', $line, $matches)) {
                    $title = trim($matches[2]);
                    $description = 'Comprehensive bachelor degree program';
                    $matched = true;
                }

                if ($matched && !empty($title)) {
                    $bachelorCount++;
                    $categorySlug = $this->detectBachelorCategory($title);

                DB::table('courses')->insert([
                    'course_code' => 'BA-' . str_pad($bachelorCount, 3, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'short_description' => $description,
                    'description' => $description . ' This bachelor degree from Cambridge British International College includes 360 credits delivered over 3-4 years.',
                    'category_id' => $categories[$categorySlug],
                    'level_id' => $levels['bachelor-degree'],
                    'duration' => '3-4 years',
                    'mode' => 'Blended',
                    'fee' => 0,
                    'status' => 'active',
                    'is_featured' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                    if ($bachelorCount >= 300) break;
                }
            }
        }

        echo "✓ Imported $bachelorCount Bachelor's Degree programs\n";

        // Parse Master's Degrees
        echo "\n[3/4] Importing Master's Degree Programs...\n";

        $masterCount = 0;
        $inMasterSection = false;

        foreach ($lines as $line) {
            if (strpos($line, "Master's Degree Programs") !== false ||
                strpos($line, "Education & Teaching (1–60)") !== false) {
                $inMasterSection = true;
                continue;
            }

            // Match Master, MBA, or LLM programs
            if ($inMasterSection && preg_match('/^\d+\.\s+((?:Master|MBA|LLM).+)$/', $line, $matches)) {
                $title = trim($matches[1]);

                $masterCount++;
                $categorySlug = $this->detectMasterCategory($title);
                $description = 'Advanced postgraduate program with research focus';
                $slug = Str::slug($title);

                // Make slug unique
                $baseSlug = $slug;
                $counter = 1;
                while (DB::table('courses')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                DB::table('courses')->insert([
                    'course_code' => 'MA-' . str_pad($masterCount, 3, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'slug' => $slug,
                    'short_description' => $description,
                    'description' => $description . '. This master degree from Cambridge British International College includes 180 credits: 8 modules plus dissertation.',
                    'category_id' => $categories[$categorySlug],
                    'level_id' => $levels['master-degree'],
                    'duration' => '12-24 months',
                    'mode' => 'Blended',
                    'fee' => 0,
                    'status' => 'active',
                    'is_featured' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($masterCount >= 300) break;
            }
        }

        echo "✓ Imported $masterCount Master's Degree programs\n";

        // Parse PhD Programs
        echo "\n[4/4] Importing PhD Programs...\n";

        $phdCount = 0;
        $inPhdSection = false;

        foreach ($lines as $line) {
            if (strpos($line, "PhD Programs") !== false ||
                strpos($line, "Education & Educational Leadership (1–35)") !== false) {
                $inPhdSection = true;
                continue;
            }

            if ($inPhdSection && preg_match('/^\d+\.\s+(PhD .+)$/', $line, $matches)) {
                $title = trim($matches[1]);

                $phdCount++;
                $categorySlug = $this->detectPhdCategory($title);
                $description = 'Advanced doctoral research program';

                DB::table('courses')->insert([
                    'course_code' => 'PHD-' . str_pad($phdCount, 3, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'short_description' => $description,
                    'description' => $description . '. This doctoral program from Cambridge British International College includes 180 credits over 2-3 years with dissertation.',
                    'category_id' => $categories[$categorySlug],
                    'level_id' => $levels['phd-doctoral-degree'],
                    'duration' => '2-3 years',
                    'mode' => 'Blended',
                    'fee' => 0,
                    'status' => 'active',
                    'is_featured' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($phdCount >= 140) break;
            }
        }

        echo "✓ Imported $phdCount PhD programs\n";

        // Final count
        $total = DB::table('courses')->count();
        echo "\n========================================\n";
        echo "✓✓✓ IMPORT COMPLETE WITH REAL DESCRIPTIONS! ✓✓✓\n";
        echo "========================================\n";
        echo "Total Courses Imported: $total\n";
        echo "- Certificates: $certificateCount (with real descriptions from file)\n";
        echo "- Bachelor's: $bachelorCount\n";
        echo "- Master's: $masterCount\n";
        echo "- PhD: $phdCount\n";
        echo "========================================\n";
    }

    private function getCategorySlug($categoryName): string
    {
        $map = [
            'Education' => 'education-teaching',
            'Business Administration' => 'business-administration',
            'Health & Safety' => 'health-safety',
            'Educational Leadership' => 'educational-leadership-management',
        ];

        return $map[$categoryName] ?? 'education-teaching';
    }

    private function detectBachelorCategory($title): string
    {
        if (stripos($title, 'Education') !== false || stripos($title, 'Teaching') !== false) {
            return 'education-teaching';
        }
        if (stripos($title, 'Leadership') !== false || stripos($title, 'Administration') !== false) {
            return 'educational-leadership-management';
        }
        if (stripos($title, 'Business') !== false || stripos($title, 'Management') !== false) {
            return 'business-administration';
        }
        if (stripos($title, 'Health') !== false || stripos($title, 'Safety') !== false) {
            return 'health-safety';
        }
        if (stripos($title, 'Law') !== false || stripos($title, 'Legal') !== false) {
            return 'law-legal-studies';
        }
        if (stripos($title, 'IT') !== false || stripos($title, 'Technology') !== false ||
            stripos($title, 'Computer') !== false || stripos($title, 'AI') !== false) {
            return 'information-technology-ai';
        }
        if (stripos($title, 'Engineering') !== false) {
            return 'engineering-technology';
        }
        if (stripos($title, 'Psychology') !== false || stripos($title, 'Sociology') !== false) {
            return 'humanities-social-sciences';
        }
        if (stripos($title, 'Nursing') !== false || stripos($title, 'Medical') !== false) {
            return 'healthcare-medical-sciences';
        }
        if (stripos($title, 'Tourism') !== false || stripos($title, 'Hospitality') !== false) {
            return 'hospitality-tourism-logistics';
        }

        return 'education-teaching';
    }

    private function detectMasterCategory($title): string
    {
        return $this->detectBachelorCategory($title);
    }

    private function detectPhdCategory($title): string
    {
        return $this->detectBachelorCategory($title);
    }
}
