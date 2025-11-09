<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the course categories and levels first (required for courses)
        $this->call([
            CourseCategorySeeder::class,
            CourseLevelSeeder::class,
        ]);

        // Seed courses (depends on categories and levels)
        $this->call(CourseSeeder::class);

        // Seed other tables
        $this->call([
            PageSeeder::class,
            SuccessStorySeeder::class,
            ContactSeeder::class,
            BannerSeeder::class,
            SettingSeeder::class,
        ]);

        // Seed admin
        $this->call(AdminSeeder::class);

        // Seed users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
