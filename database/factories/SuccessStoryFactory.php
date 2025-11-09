<?php

namespace Database\Factories;

use App\Models\SuccessStory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuccessStory>
 */
class SuccessStoryFactory extends Factory
{
    protected $model = SuccessStory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_name' => fake()->name(),
            'country' => fake()->country(),
            'title' => fake()->sentence(6),
            'story' => fake()->paragraphs(4, true),
            'image' => fake()->imageUrl(400, 400, 'people'),
            'is_published' => fake()->boolean(90),
        ];
    }

    /**
     * Indicate that the story is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }
}
