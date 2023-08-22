<?php

namespace Database\Seeders;

use App\Models\TimelineQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TimelineQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::isProduction()) {
            TimelineQuestion::factory(10)->withTimeline()->create()->each(function (TimelineQuestion $question) {
                $question->addMediaFromUrl('https://picsum.photos/640/480')
                    ->withResponsiveImages()
                    ->toMediaCollection('cover');
            });

            return;
        }

        $data = [
            'The elders in our life have a tremendous impact on how we grow into our personalities.   What is a memorable moment you had with a grandparent?  How did it impact your personality in the present?',
            'The first meeting of a romantic partner is always memorable after the fact.  You generally never expect the impact they will have on you.  Tell us about the time you met your romantic partner.  Go into detail describing the setting, the person, and how it made you feel.',
            'Travel has a way of producing tremendous growth in every day life.  Leaving our homes and visiting a new location always leaves everlasting memories.  What was the most memorable travel story of your childhood.  Go into deep detail about the location, the activities, and how you feel about it today.',
        ];

        foreach ($data as $datum) {
            $question = TimelineQuestion::create([
                'timeline_id' => 1,
                'question' => $datum,
            ]);

            $question->addMediaFromUrl('https://picsum.photos/640/480')
                ->withResponsiveImages()
                ->toMediaCollection('cover');
        }
    }
}
