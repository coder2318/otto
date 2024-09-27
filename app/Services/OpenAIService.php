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
                'model' => $this->modelName,
                'messages' => $messages,
                'temperature' => 0.8,
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

        $languageName = $this->getLanguageName($input);

        if (is_null($prompt)) {
            $prompt = Prompt::where('title', 'The Default')->value('content') ?? $this->defaultPrompt;
        }

        $prompt = $this->cleanPrompt($prompt);

        $strings = [
            'name' => "What's your name?",
            'question' => "Please, tell me your story on: \"$question\"",
        ];

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
                'role' => 'system',
                'content' => "[RULE] Now assistant using only {$languageName} language",
            ];

            $messages[] = [
                'role' => 'system',
                'content' => '[RULE] dont damage the HTML markup.',
            ];

            $messages[] = [
                'role' => 'system',
                'content' => '[RULE] You cant change links and links params in the text.',
            ];

            $messages[] = [
                'role' => 'system',
                'content' => "[RULE] Use HTML instead of a markdown in the output.",
            ];

            $messages[] = [
                'role' => 'system',
                'content' => '[RULE] Please completely rephrase the following text using different words and sentence structures. Ensure that no phrases or chunks from the original text are repeated unless absolutely necessary for clarity. Maintain the original meaning and key points.',
            ];

            $messages[] = [
                'role' => 'user',
                'content' => $segment,
            ];

            foreach ($this->chatCreateStreamed($messages) as $response) {
                yield $response;
            }

            yield PHP_EOL.PHP_EOL;
        }

        return true;
    }

    public function getLanguageName($text)
    {
        $text = strip_tags($text);
        $text = mb_substr($text, 0, 500);

        $messages[] = [
            'role' => 'system',
            'content' => '[RULE] determine the language and return the language name, the response should only contain the name of the language',
        ];

        $messages[] = [
            'role' => 'user',
            'content' => $text,
        ];

        $response = OpenAI::chat()->create([
            'model' => $this->modelName,
            'messages' => $messages,
            'temperature' => 0.8,
        ]);

        return $response['choices'][0]['message']['content'] ?? '';
    }

    public function translateTextStreamed(string $text, string $target)
    {
        $messages[] = [
            'role' => 'system',
            'content' => "[RULE] translate text to languageCode:{$target}.",
        ];

        $messages[] = [
            'role' => 'system',
            'content' => '[RULE] dont damage the HTML markup.',
        ];

        $messages[] = [
            'role' => 'system',
            'content' => '[RULE] You cant change links and links params in the text.',
        ];

        $messages[] = [
            'role' => 'user',
            'content' => $text,
        ];

        foreach ($this->chatCreateStreamed($messages) as $response) {
            yield $response;
        }

        return true;
    }

    protected function cleanPrompt(string $prompt) {
        // Remove new lines (both \r\n and \n)
        $text = preg_replace('/\s+/', ' ', $prompt);

        // Trim any leading or trailing spaces
        return trim($text);
    }

    protected function chatCreateStreamed(array $messages)
    {
        $answer = '';
        $stream = OpenAI::chat()->createStreamed([
            'model' => $this->modelName,
            'messages' => $messages,
            'temperature' => 0.8,
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

                yield from $this->chatCreateStreamed($messages);

                continue;
            } else {
                yield $response['choices'][0]['delta']['content'] ?? '';
            }
        }
    }
}
