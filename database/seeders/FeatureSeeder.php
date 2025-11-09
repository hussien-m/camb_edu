<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'Expert Trainers',
                'description' => 'Learn from experienced instructors with global exposure and industry expertise.',
                'icon' => 'fas fa-chalkboard-teacher',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Flexible Learning',
                'description' => 'Study online anytime, anywhere, at your own pace with our modern learning platform.',
                'icon' => 'fas fa-laptop-house',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Certification',
                'description' => 'Receive globally recognized certificates after course completion to boost your career.',
                'icon' => 'fas fa-certificate',
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Community Support',
                'description' => 'Join a vibrant community of learners and get support when you need it.',
                'icon' => 'fas fa-users',
                'order' => 4,
                'is_active' => true
            ],
            [
                'title' => 'Comprehensive Content',
                'description' => 'Access to rich, up-to-date course materials and resources for better learning.',
                'icon' => 'fas fa-book-open',
                'order' => 5,
                'is_active' => true
            ],
            [
                'title' => '24/7 Support',
                'description' => 'Our dedicated support team is always ready to help you succeed.',
                'icon' => 'fas fa-headset',
                'order' => 6,
                'is_active' => true
            ]
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
