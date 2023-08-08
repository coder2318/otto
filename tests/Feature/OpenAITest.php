<?php

namespace Tests\Feature;

use App\Services\OpenAIService;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Edits\CreateResponse;
use Tests\TestCase;

class OpenAITest extends TestCase
{
    public function test_edits(): void
    {
        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    ['text' => 'What day of the week is it?'],
                ],
            ]),
        ]);

        /** @var OpenAIService */
        $service = $this->app->make(OpenAIService::class);

        $this->assertEquals('What day of the week is it?', $service->edit('What day of the wek is it?', 'Fix the spelling mistakes'));
    }
}
