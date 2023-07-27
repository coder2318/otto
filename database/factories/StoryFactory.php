<?php

namespace Database\Factories;

use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'cover' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement([Status::PUBLISHED, Status::DRAFT]),
        ];
    }
}
