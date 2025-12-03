<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class AddFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'Expert Instructors',
                'description' => 'Learn from industry experts with years of real-world experience and proven track records in their fields.',
                'icon' => 'fas fa-chalkboard-teacher',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Flexible Learning',
                'description' => 'Study at your own pace with 24/7 access to course materials, videos, and resources from anywhere.',
                'icon' => 'fas fa-laptop-code',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Certified Programs',
                'description' => 'Earn recognized certificates upon completion that enhance your career prospects and professional credibility.',
                'icon' => 'fas fa-certificate',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Lifetime Support',
                'description' => 'Get ongoing support from our community and instructors even after completing your courses.',
                'icon' => 'fas fa-headset',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Interactive Content',
                'description' => 'Engage with interactive quizzes, assignments, and hands-on projects that make learning fun and effective.',
                'icon' => 'fas fa-tasks',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Career Guidance',
                'description' => 'Receive personalized career advice and job placement assistance to help you achieve your professional goals.',
                'icon' => 'fas fa-briefcase',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['title' => $feature['title']],
                $feature
            );
        }

        $this->command->info('✅ 6 beautiful features have been added successfully!');
    }
}

