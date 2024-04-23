<?php

namespace App\Http\Controllers;

use App\Services\Claude3Service;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TestController extends Controller
{
    public function prompt(Request $request, Claude3Service $service)
    {
        $request->validate([
            'prompt' => 'required|string',
            'question' => 'required|string',
            'content' => 'required|string',
        ]);

        return new StreamedResponse(function () use ($request, $service) {
            $generator = $service->chatEditStreamed(
                prompt: $request->prompt,
                question: $request->question,
                input: $request->content,
            );

            foreach ($generator as $chunk) {
                if (connection_aborted()) {
                    return;
                }

                echo $chunk;

                ob_flush();
                flush();
            }
        }, headers: ['X-Accel-Buffering' => 'no', 'Content-Type' => 'text/plain']);
    }
}
