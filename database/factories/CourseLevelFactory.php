<?php

namespace Database\Factories;

use App\Models\CourseLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseLevel>
 */
class CourseLevelFactory extends Factory
{
    protected $model = CourseLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
