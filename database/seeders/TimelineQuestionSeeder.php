<?php

namespace Database\Seeders;

use App\Models\TimelineQuestion;
use Illuminate\Database\Seeder;

class TimelineQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimelineQuestion::factory(10)->withTimeline()->create()->each(function (TimelineQuestion $question) {
            $question->addMediaFromUrl('https://picsum.photos/640/480')
                ->withResponsiveImages()
                ->toMediaCollection('cover');
        });
    }
}
