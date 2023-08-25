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
        if (! App::isProduction()) {
            TimelineQuestion::factory(10)->withTimeline()->create()->each(function (TimelineQuestion $question) {
                $question->addMediaFromUrl('https://picsum.photos/640/480')
                    ->withResponsiveImages()
                    ->toMediaCollection('cover');
            });

            return;
        }

        $data = [
            'What has been the most memorable relationship of your teenage years - whether romantic or friendly?',
            'What is your fondest memory with a grandparent?',
            'How has your philosophy on life changed from your childhood until now?',
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
