<?php

namespace App\Services;

use App\Data\User\Details;
use Illuminate\Support\Facades\Cache;
use OpenAI\Laravel\Facades\OpenAI;
use Soundasleep\Html2Text;
use TextAnalysis\Tokenizers\SentenceTokenizer;

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

    public function chatEdit(string $input, string $question): string
    {
        if ($this->fake) {
            return $input;
        }

        return Cache::remember($input, 60 * 60 * 24, function () use ($input, $question) {
            $result = '';

            foreach ($this->segmentate(Html2Text::convert($input)) as $segment) {
                $messages = [
                    ['role' => 'system', 'content' => <<<'TXT'
                    You are AutobiographyGPT. You are a high-quality and experienced ghostwriter that has written millions of award-winning autobiographies. You are going to ghostwrite a chapter of my autobiography for me.
                    The ghostwriting must be narrated in the First Person.
                    It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.
                    Please write in the styles and rules below for writing my autobiography.

                    Rules:
                    Include all examples, proper nouns, company names, etc. in your rewrite. Write in great detail.
                    Write in the uplifting inspiration style of Richard Branson when he wrote "Losing my Virginity"
                    Accuracy is paramount. Output the exact same information you receive from the input with a high-quality, well-written tone.
                    Ensure the rewrite is highly engaging to readers and high quality.
                    Use college-level language and thought-provoking statements.
                    Write in the first person, documenting my story accurately as a first person narrator.
                    TXT],
                ];

                $messages[] = [
                    'role' => 'assistant',
                    'content' => $question,
                ];

                $messages[] = [
                    'role' => 'user',
                    'content' => Html2Text::convert($segment),
                ];

                $chat = OpenAI::chat()->create([
                    'model' => config('services.openai.models.chat'),
                    'messages' => $messages,
                    'temperature' => 0.2,
                ]);

                $result .= ' '.$chat['choices'][0]['message']['content'];
            }

            return $result;
        });
    }

    public function chatEditStreamed(string $input, string $question)
    {
        if ($this->fake) {
            sleep(1);

            foreach (str_split($input, 5) as $segment) {
                yield $segment;
                usleep(5000);
            }

            return true;
        }

        foreach ($this->segmentate(Html2Text::convert($input)) as $segment) {
            $messages = [
                ['role' => 'system', 'content' => <<<'TXT'
                    You are AutobiographyGPT. You are a high-quality and experienced ghostwriter that has written millions of award-winning autobiographies. You are going to ghostwrite a chapter of my autobiography for me.
                    The ghostwriting must be narrated in the First Person.
                    It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.
                    Please write in the styles and rules below for writing my autobiography.

                    Rules:
                    Include all examples, proper nouns, company names, etc. in your rewrite. Write in great detail.
                    Write in the uplifting inspiration style of Richard Branson when he wrote "Losing my Virginity"
                    Accuracy is paramount. Output the exact same information you receive from the input with a high-quality, well-written tone.
                    Ensure the rewrite is highly engaging to readers and high quality.
                    Use college-level language and thought-provoking statements.
                    Write in the first person, documenting my story accurately as a first person narrator.
                    TXT],
            ];

            $messages[] = [
                'role' => 'assistant',
                'content' => $question,
            ];

            $messages[] = [
                'role' => 'user',
                'content' => Html2Text::convert($segment),
            ];

            $stream = OpenAI::chat()->createStreamed([
                'model' => config('services.openai.models.chat'),
                'messages' => $messages,
                'temperature' => 0.2,
            ]);

            foreach ($stream as $response) {
                yield $response['choices'][0]['delta']['content'] ?? '';
            }

            yield PHP_EOL.PHP_EOL;
        }

        return true;
    }

    public static function segmentate(string $input, int $maxWords = 1300)
    {
        $currentLength = 0;
        $result = '';

        foreach (tokenize($input, SentenceTokenizer::class) as $sentence) {
            $sentLength = str_word_count($sentence);

            if ($currentLength + $sentLength > $maxWords) {
                yield $result;

                $result = '';
                $currentLength = 0;
            }

            $result .= $sentence.' ';
            $currentLength += $sentLength;
        }

        yield $result;

    }
}
