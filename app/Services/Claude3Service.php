<?php

namespace App\Services;

use Anthropic\Anthropic;
use App\Models\Prompt;

class Claude3Service extends AiService
{
    protected $anthropic;

    public function __construct(string $apiKey, bool $segmentate)
    {
        $headers = [
            'anthropic-version' => '2023-06-01',
            'anthropic-beta' => 'messages-2023-12-15',
            'content-type' => 'application/json',
            'x-api-key' => $apiKey,
        ];

        $this->anthropic = Anthropic::factory()
            ->withHeaders($headers)
            ->make();
        $this->segmentate = $segmentate;
    }

    public function chatEditStreamed(string $input, string $question, ?string $prompt = null, ?string $name = null)
    {
        $language = $this->getLanguage($input);
        if (is_null($prompt)) {
            $prompt = Prompt::where('title', 'The Default')->value('content') ?? $this->defaultPrompt;
        }

        $prompt .= "[RULE] Now assistant using only {$language} language";
        $prompt .= "[RULE] dont damage the HTML markup.";
        $prompt .= "[RULE] You cant change links and links params in the text.";
        $prompt .= "[RULE] Use HTML instead of a markdown in the output.";
        $prompt .= "[RULE] Please completely rephrase the following text using different words and sentence structures. Ensure that no phrases or chunks from the original text are repeated unless absolutely necessary for clarity. Maintain the original meaning and key points.";
        $prompt = $this->getPrompt($prompt, $language);

        $strings = [
            'name' => $this->translate("What's your name?", $language),
            'question' => $this->translate("Please, tell me your story on: \"$question\"", $language),
        ];

        foreach ($this->segmentate ? self::segmentate($input) : [$input] as $segment) {
            $messages = [
                ['role' => 'user', 'content' => $prompt],
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

            $stream = $this->anthropic->chat()->createStreamed([
                'model' => 'claude-3-opus-20240229',
                'system' => $prompt,
                'temperature' => 0.8,
                'messages' => $messages,
                'max_tokens' => 4096,
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->delta->content;
                yield $text;
            }
        }
    }

    public function translateTextStreamed(string $text, string $target)
    {
        $prompt = "[RULE] translate the following text to {$target} language. Make sure not to alter the meaning of the content.";

        foreach ($this->segmentate ? self::segmentate($text) : [$text] as $segment) {
            $messages = [
                ['role' => 'user', 'content' => $segment],
            ];

            $stream = $this->anthropic->chat()->createStreamed([
                'model' => 'claude-3-opus-20240229',
                'system' => $prompt,
                'temperature' => 0.8,
                'messages' => $messages,
                'max_tokens' => 4096,
            ]);

            foreach ($stream as $response) {
                $translatedText = $response->choices[0]->delta->content;
                yield $translatedText;
            }
        }
    }
}
