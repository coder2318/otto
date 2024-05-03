<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService extends AiService
{
    public function __construct(protected ?bool $fake = null)
    {
        $this->fake = is_null($this->fake) ? config('services.openai.fake') : $this->fake;
    }

    public function chatEdit(string $input, string $question, ?string $prompt = null): string
    {
        if ($this->fake) {
            return $input;
        }
        $result = '';
        $model = config('services.openai.models.chat');

        foreach (self::segmentate($input) as $segment) {
            $messages = [
                ['role' => 'system', 'content' => $this->getPrompt($input, $prompt ?? $this->defaultPrompt)],
            ];

            $messages[] = [
                'role' => 'assistant',
                'content' => $question,
            ];

            $messages[] = [
                'role' => 'user',
                'content' => $segment,
            ];

            $chat = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.2,
            ]);

            $result .= ' '.$chat['choices'][0]['message']['content'];
        }

        return trim($result);
    }

    public function chatEditStreamed(string $input, string $question, ?string $prompt = null, ?string $name = null)
    {
        if ($this->fake) {
            sleep(1);

            foreach (str_split($input, 5) as $segment) {
                yield $segment;
                usleep(5000);
            }

            return true;
        }

        $language = $this->getLanguage($input);
        $prompt = $this->getPrompt($prompt ?? $this->defaultPrompt, $language);
        $strings = [
            'name' => $this->translate("What's your name?", $language),
            'question' => $this->translate("Please, tell me your story on: \"$question\"", $language),
        ];
        $model = config('services.openai.models.chat');

        foreach (self::segmentate($input) as $segment) {
            $messages = [
                ['role' => 'system', 'content' => $prompt],
            ];

            if ($name) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $strings['name'],
                ];

                $messages[] = [
                    'role' => 'user',
                    'content' => $name,
                ];
            }

            $messages[] = [
                'role' => 'assistant',
                'content' => $strings['question'],
            ];

            $messages[] = [
                'role' => 'user',
                'content' => $segment,
            ];

            $stream = OpenAI::chat()->createStreamed([
                'model' => $model,
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
}
