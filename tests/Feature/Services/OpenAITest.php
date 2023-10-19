<?php

namespace Tests\Feature\Services;

use App\Services\OpenAIService;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use Tests\TestCase;

class OpenAITest extends TestCase
{
    /** @test */
    public function edits(): void
    {
        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    ['message' => ['content' => 'What day of the week is it?']],
                ],
            ]),
        ]);

        /** @var OpenAIService */
        $service = new OpenAIService(fake: false);

        $this->assertEquals('What day of the week is it?', $service->chatEdit('What day of the wek is it?', 'Fix the spelling mistakes'));
    }
}
