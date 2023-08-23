<?php

namespace App\Services;

use App\Data\User\Details;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Collection;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    protected bool $fake;

    public function __construct()
    {
        $this->fake = config('services.openai.fake');
    }

    public function edit(string $input, string $instruction): string
    {
        if ($this->fake) {
            return $input;
        }

        $edit = OpenAI::edits()->create(['model' => config('services.openai.models.edits')] + compact('input', 'instruction'));

        return $edit['choices'][0]['text'];
    }

    public function createInstractions(?Details $details): string
    {
        $instructions = [
            'Fix the spelling mistakes.',
        ];

        // TODO: Enchance writing using user details

        return implode(' ', $instructions);
    }

    public function chatEdit(string $input, ?Details $details): string
    {
        if ($this->fake) {
            return $input;
        }

        $messages = [
            ['role' => 'system', 'content' => 'You are a writing editor. Fix spelling mistakes and enhance user writing based on his answers.'],
        ];

        /** @var Collection */
        $questions = QuizQuestion::whereIn('id', array_keys($details->quiz))
            ->get(['id', 'question'])
            ->mapWithKeys(fn (QuizQuestion $question) => [$question->id => $question->question]);

        foreach ($details?->quiz ?? [] as $questionId => $answer) {
            $messages[] = [
                'role' => 'editor',
                'content' => $questions->get($questionId),
            ];

            $messages[] = [
                'role' => 'user',
                'content' => $answer,
            ];
        }

        $messages[] = [
            'role' => 'editor',
            'content' => 'What text should I edit?',
        ];

        $messages[] = [
            'role' => 'user',
            'content' => $input,
        ];

        $chat = OpenAI::chat()->create([
            'model' => config('services.openai.models.chat'),
            'messages' => $messages,
        ]);

        return $chat['choices'][0]['message']['content'];
    }
}
