<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Audit & Assurance Diploma',
                'slug' => 'audit-assurance-diploma',
                'category_id' => 1, // Accounting, Finance, Banking
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Business Management & Administration Diploma',
                'slug' => 'business-management-administration-diploma',
                'category_id' => 2, // Business Studies, Insurance, Law
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'International Business & Trade Diploma',
                'slug' => 'international-business-trade-diploma',
                'category_id' => 2, // Business Studies, Insurance, Law
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Business Administration (EBA)',
                'slug' => 'business-administration-eba',
                'category_id' => 2, // Business Studies, Insurance, Law
                'level_id' => 6, // EBA: Executive Business Administration
                'duration' => '3 years (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Strategic Management (EMBA)',
                'slug' => 'strategic-management-emba',
                'category_id' => 2, // Business Studies, Insurance, Law
                'level_id' => 9, // EMBA: Executive Mastery of Business Administration
                'duration' => '3 years (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Health & Safety in the Workplace Diploma',
                'slug' => 'health-safety-workplace-diploma',
                'category_id' => 6, // Management, Administration, Leadership
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Leadership & Team Management Diploma',
                'slug' => 'leadership-team-management-diploma',
                'category_id' => 6, // Management, Administration, Leadership
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Human Resource & Personnel Management Diploma',
                'slug' => 'human-resource-personnel-management-diploma',
                'category_id' => 8, // HR, Organisation, Education & Teaching
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
            [
                'title' => 'Training & Development Diploma',
                'slug' => 'training-development-diploma',
                'category_id' => 8, // HR, Organisation, Education & Teaching
                'level_id' => 2, // Diploma
                'duration' => '12 months (flexible)',
                'status' => 'active',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
