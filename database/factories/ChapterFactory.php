<?php

namespace Database\Factories;

use App\Data\Chapter\Status;
use App\Models\Story;
use App\Models\Timeline;
use App\Models\TimelineQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
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
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(array_column(Status::cases(), 'value')),
        ];
    }

    public function withTimeline(?int $id = null): static
    {
        return $this->state(fn (array $attributes) => [
            'timeline_id' => $id ?? Timeline::inRandomOrder()->value('id'),
        ]);
    }

    public function withStory(?int $id = null): static
    {
        return $this->state(fn (array $attributes) => [
            'story_id' => $id ?? Story::inRandomOrder()->value('id'),
        ]);
    }

    public function withTimelineQuestion(?int $id = null): static
    {
        return $this->state(fn (array $attributes) => [
            'timeline_question_id' => $id ?? TimelineQuestion::inRandomOrder()->value('id'),
        ]);
    }
}
