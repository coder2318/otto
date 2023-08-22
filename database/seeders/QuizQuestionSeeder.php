<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ( App::isProduction()) {
            QuizQuestion::factory(5)->create();
            return;
        }

        $data = [
            [
                'question' => 'What is your primary motivation or reason for wanting to share your autobiography at this stage in your life?',
                'answers' => [
                    'Inspire others',
                    'Share experiences',
                    'Preserve memories',
                    'Connect with others',
                ]
            ],
            [
                'question' => 'How would you describe your writing style and tone?',
                'answers' => [
                    'Conversational tone',
                    'Engaging storytelling',
                    'Thoughtful and expressive',
                    'Informal yet engaging',
                ]
            ],
            [
                'question' => 'What are your goals for sharing your story on our platform?',
                'answers' => [
                    'Inspire and empower',
                    'Share life lessons',
                    'Engage and interact',
                    'Spark conversations',
                ]
            ],
            [
                'question' => 'Is there a desired timeline or deadline you have in mind for completing and publishing your autobiography?',
                'answers' => [
                    '6 months',
                    '9 months',
                    'Longer than a year',
                ]
            ]
        ];

        foreach ($data as $datum) {
            QuizQuestion::create($datum);
        }
    }
}
