<?php

namespace Tests\Feature;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Edits\CreateResponse;
use Tests\TestCase;

class OpenAITest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_edits(): void
    {
        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    ['text' => 'What day of the week is it?'],
                ],
            ]),
        ]);

        $edit = OpenAI::edits()->create([
            'model' => config('openai.models.edits'),
            'prompt' => 'What day of the wek is it?',
            'instruction' => 'Fix the spelling mistakes',
        ]);

        $this->assertEquals('What day of the week is it?', $edit['choices'][0]['text']);
    }
}
