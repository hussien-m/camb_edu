<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'category_id' => CourseCategory::factory(),
            'level_id' => CourseLevel::factory(),
            'short_description' => fake()->sentence(10),
            'description' => fake()->paragraphs(3, true),
            'duration' => fake()->randomElement(['6 months', '12 months', '18 months', '2 years', '3 years']) . ' (flexible)',
            'mode' => fake()->randomElement(['online', 'offline', 'hybrid']),
            'fee' => fake()->randomFloat(2, 500, 5000),
            'image' => fake()->imageUrl(640, 480, 'education'),
            'is_featured' => fake()->boolean(20),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Indicate that the course is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the course is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
