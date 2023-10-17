<?php

namespace Database\Factories;

use App\Models\Timeline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimelineQuestion>
 */
class TimelineQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(),
            'context' => $this->faker->sentence(),
            'sub_questions' => $this->faker->sentences(5),
        ];
    }

    public function withTimeline(int $id = null): static
    {
        return $this->state(fn (array $attributes) => [
            'timeline_id' => $id ?? Timeline::inRandomOrder()->value('id'),
        ]);
    }
}
