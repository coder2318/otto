<?php

namespace App\Services;

use Anthropic\Anthropic;

class Claude3Service extends AiService
{
    protected $anthropic;

    public function __construct(string $apiKey)
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
    }

    public function chatEditStreamed(string $input, string $question, ?string $prompt = null, ?string $name = null)
    {
        $language = $this->getLanguage($input);
        $prompt = $this->getPrompt($prompt ?? $this->defaultPrompt, $language);

        $strings = [
            'name' => $this->translate("What's your name?", $language),
            'question' => $this->translate("Please, tell me your story on: \"$question\"", $language),
        ];

        $segments = self::segmentate($input);

        foreach ($segments as $segment) {
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
                'temperature' => 1,
                'messages' => $messages,
                'max_tokens' => 4096,
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->delta->content;
                yield $text;
            }
        }
    }
}
