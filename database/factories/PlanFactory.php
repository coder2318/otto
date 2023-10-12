<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(2),
            'slug' => fake()->slug(),
            'prices' => [[
                'interval' => fake()->randomElement(['month', 'year']),
                'interval_count' => 1,
                'value' => fake()->randomFloat(2, 10, 100),
                'currency' => 'usd',
            ]],
            'features' => [],
            'description' => fake()->text(),
        ];
    }
}
