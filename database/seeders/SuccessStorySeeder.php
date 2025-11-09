<?php

namespace Database\Seeders;

use App\Models\SuccessStory;
use Illuminate\Database\Seeder;

class SuccessStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stories = [
            [
                'student_name' => 'Florence Nako',
                'country' => 'Vanuatu',
                'story' => 'I was very happy when I received my Diploma and knew at once that I was taking the right path to what would become a successful life if I continue with CIC. I plan to continue studying with CIC because I find that the approach to learning really suits my daily routine.',
                'is_published' => true,
            ],
        ];

        foreach ($stories as $story) {
            SuccessStory::create($story);
        }
    }
}
