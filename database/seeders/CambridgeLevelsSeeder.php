<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CambridgeLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Certificate',
                'slug' => 'certificate',
                'description' => 'Short professional certificate programs - 20 credits',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional Diploma',
                'slug' => 'professional-diploma',
                'description' => 'Comprehensive professional diploma programs - 180 credits (14 months)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bachelor\'s Degree',
                'slug' => 'bachelor-degree',
                'description' => 'Undergraduate bachelor degree programs - 360 credits (3-4 years)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Master\'s Degree',
                'slug' => 'master-degree',
                'description' => 'Postgraduate master degree programs - 180 credits (12-24 months)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PhD (Doctoral Degree)',
                'slug' => 'phd-doctoral-degree',
                'description' => 'Doctoral research programs - 180 credits (2-3 years)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('course_levels')->insert($levels);
    }
}
