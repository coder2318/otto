<?php

namespace App\Services;

use App\Data\User\Details;
use App\Models\QuizQuestion;
use App\Models\TimelineQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
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

        // TODO: enhance writing using user details

        return implode(' ', $instructions);
    }

    public function chatEdit(string $input, ?TimelineQuestion $question, ?Details $details): string
    {
        if ($this->fake) {
            return $input;
        }

        return Cache::remember($input, 60 * 60 * 24, function () use ($input, $question, $details) {

            $messages = [
                ['role' => 'system', 'content' => <<<'TXT'
                You are AutobiographyGPT. You are a high-quality and experienced ghostwriter that has written millions of award-winning autobiographies. You are going to ghostwrite a chapter of my autobiography for me.
                The ghostwriting must be narrated in the First Person.
                Please write in the styles and rules below for writing my autobiography. The chapter is about my reflections on my most memorable relationship of your teenage years - whether romantic or friendly?  .   It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.
                It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.
                Should output in HTML.

                Rules:
                Include all examples, proper nouns, company names, etc. in your rewrite. Write in great detail.
                Write in the uplifting inspiration style of Richard Branson when he wrote "Losing my Virginity"
                Accuracy is paramount. Output the exact same information you receive from the input with a high-quality, well-written tone.
                Ensure the rewrite is highly engaging to readers and high quality.
                Use college-level language and thought-provoking statements.
                Write in the first person, documenting my story accurately as a first person narrator.
                TXT],
            ];

            if ($question?->question) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => 'What questions this chapter should answer?',
                ];

                $messages[] = [
                    'role' => 'user',
                    'content' => $question->question,
                ];
            }

            /** @var Collection */
            $questions = QuizQuestion::whereIn('id', array_keys($details->quiz))
                ->get(['id', 'question'])
                ->mapWithKeys(fn (QuizQuestion $question) => [$question->id => $question->question]);

            foreach ($details?->quiz ?? [] as $questionId => $answer) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $questions->get($questionId),
                ];

                $messages[] = [
                    'role' => 'user',
                    'content' => $answer,
                ];
            }

            $messages[] = [
                'role' => 'assistant',
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
        });
    }
}
