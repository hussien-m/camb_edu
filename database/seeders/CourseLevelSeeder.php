<?php

namespace Database\Seeders;

use App\Models\CourseLevel;
use Illuminate\Database\Seeder;

class CourseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Certificate',
                'slug' => 'certificate',
                'sort_order' => 1,
            ],
            [
                'name' => 'Diploma',
                'slug' => 'diploma',
                'sort_order' => 2,
            ],
            [
                'name' => 'Honours (Higher) Diploma',
                'slug' => 'honours-higher-diploma',
                'sort_order' => 3,
            ],
            [
                'name' => 'ABA: Advanced Business Administration',
                'slug' => 'aba-advanced-business-administration',
                'sort_order' => 4,
            ],
            [
                'name' => 'Baccalaureate',
                'slug' => 'baccalaureate',
                'sort_order' => 5,
            ],
            [
                'name' => 'EBA: Executive Business Administration',
                'slug' => 'eba-executive-business-administration',
                'sort_order' => 6,
            ],
            [
                'name' => 'Mastery of Management Graduate Diploma',
                'slug' => 'mastery-of-management-graduate-diploma',
                'sort_order' => 7,
            ],
            [
                'name' => 'Executive Mini MBA',
                'slug' => 'executive-mini-mba',
                'sort_order' => 8,
            ],
            [
                'name' => 'EMBA: Executive Mastery of Business Administration',
                'slug' => 'emba-executive-mastery-of-business-administration',
                'sort_order' => 9,
            ],
            [
                'name' => 'Joint ILM City & Guilds & CIC Awards (Higher, ABA, EBA, EMBA)',
                'slug' => 'joint-ilm-city-guilds-cic-awards',
                'sort_order' => 10,
            ],
        ];

        foreach ($levels as $level) {
            CourseLevel::create($level);
        }
    }
}
