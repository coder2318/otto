<?php

namespace App\Services;

use App\Data\User\Details;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    protected bool $fake;

    public function __construct()
    {
        $this->fake = config('services.openai.fake');
    }

    public function edit(string $prompt, string $instruction): string
    {
        if ($this->fake) {
            return $prompt;
        }

        $edit = OpenAI::edits()->create(['model' => config('services.openai.models.edits')] + compact('prompt', 'instruction'));

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
}
