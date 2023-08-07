<?php

namespace Tests\Unit;

use App\Services\DeepgramService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeepgramTest extends TestCase
{
    public function test_transcript_url(): void
    {
        Http::fake([
            'api.deepgram.com/v1/*' => Http::response(
                $data = json_decode(file_get_contents(__DIR__ . '/../Mock/DeepgramResponse.json'), true),
                200, ['Content-Type' => 'application/json']
            ),
        ]);

        /** @var DeepgramService */
        $service = app(DeepgramService::class);

        $transcript = $service->transcribeFromUrl('https://static.deepgram.com/examples/interview_speech-analytics.wav');

        $this->assertIsString($transcript);
        $this->assertEquals($data['results']['channels'][0]['alternatives'][0]['transcript'], $transcript);
    }
}
