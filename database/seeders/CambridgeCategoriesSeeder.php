<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CambridgeCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Education & Teaching',
                'slug' => 'education-teaching',
                'description' => 'Comprehensive programs in education, teaching methodologies, curriculum development, and educational leadership',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Administration',
                'slug' => 'business-administration',
                'description' => 'Business management, administration, leadership, entrepreneurship, and organizational development programs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health & Safety',
                'slug' => 'health-safety',
                'description' => 'Occupational health, workplace safety, environmental health, and safety management programs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Educational Leadership & Management',
                'slug' => 'educational-leadership-management',
                'description' => 'Leadership in education, school management, educational administration, and policy development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Law & Legal Studies',
                'slug' => 'law-legal-studies',
                'description' => 'Legal studies, criminology, criminal justice, security management, and law enforcement programs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Information Technology & AI',
                'slug' => 'information-technology-ai',
                'description' => 'IT, computer science, artificial intelligence, cybersecurity, data science, and software development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Engineering & Technology',
                'slug' => 'engineering-technology',
                'description' => 'Engineering disciplines, project management, construction management, and technical programs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Humanities & Social Sciences',
                'slug' => 'humanities-social-sciences',
                'description' => 'Psychology, sociology, counseling, social work, history, philosophy, and cultural studies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Healthcare & Medical Sciences',
                'slug' => 'healthcare-medical-sciences',
                'description' => 'Healthcare management, nursing, public health, medical sciences, and healthcare administration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hospitality, Tourism & Logistics',
                'slug' => 'hospitality-tourism-logistics',
                'description' => 'Hospitality management, tourism, logistics, supply chain, and service industry programs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('course_categories')->insert($categories);
    }
}
