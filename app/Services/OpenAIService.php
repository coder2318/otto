<?php

namespace App\Services;

use App\Models\Prompt;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService extends AiService
{
    protected $modelName;

    public function __construct(protected ?bool $fake, $modelName, bool $segmentate)
    {
        $this->fake = is_null($this->fake) ? config('services.openai.fake') : $this->fake;
        $this->fake = false;
        $this->modelName = $modelName;
        $this->segmentate = $segmentate;
    }

    public function chatEdit(string $input, string $question, ?string $prompt = null): string
    {
        if ($this->fake) {
            return $input;
        }
        $result = '';
        $model = $this->modelName;

        foreach ($this->segmentate ? self::segmentate($input) : [$input] as $segment) {
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
        if (is_null($prompt)) {
            $prompt = Prompt::where('title', 'The Default')->value('content') ?? $this->defaultPrompt;
        }
        $prompt = $this->getPrompt($prompt, $language);

        $strings = [
            'name' => $this->translate("What's your name?", $language),
            'question' => $this->translate("Please, tell me your story on: \"$question\"", $language),
        ];
        $model = $this->modelName;

        foreach ($this->segmentate ? self::segmentate($input) : [$input] as $segment) {
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

            foreach ($this->chatCreateStreamed($model, $messages) as $response) {
                yield $response;
            }

            yield PHP_EOL.PHP_EOL;
        }

        return true;
    }

    protected function chatCreateStreamed(string $model, array $messages)
    {
        $answer = '';
        $stream = OpenAI::chat()->createStreamed([
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.2,
        ]);

        foreach ($stream as $response) {
            $finishReason = $response['choices'][0]['finish_reason'] ?? null;
            $answer .= $response['choices'][0]['delta']['content'] ?? '';

            if ($finishReason == 'length') {
                sleep(2);

                $messages[] = [
                    'role' => 'assistant',
                    'content' => $answer,
                ];
                $messages[] = [
                    'role' => 'user',
                    'content' => 'continue your answer',
                ];

                yield from $this->chatCreateStreamed($model, $messages);

                continue;
            } else {
                yield $response['choices'][0]['delta']['content'] ?? '';
            }
        }
    }
}
