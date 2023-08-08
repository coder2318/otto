<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Http;

class DeepgramService
{
    protected bool $fake;

    protected array $query = [
        'punctuate' => 'true',
        'smart_format' => 'true',
    ];

    public function __construct()
    {
        $this->fake = config('services.deepgram.fake');
    }

    protected function client()
    {
        return Http::baseUrl('https://api.deepgram.com/v1')
            ->acceptJson()
            ->withToken(config('services.deepgram.key'), 'Token')
            ->withQueryParameters($this->query);
    }

    public function transcribeFromUrl(string $url): ?string
    {
        return $this->fake
            ? 'This is a fake Deepgram transcript.'
            : $this->client()->asJson()
                ->post('listen', compact('url'))
                ->json('results.channels.0.alternatives.0.transcript');
    }

    public function transcribeFromFile(FilesystemAdapter $storage, string $path): ?string
    {
        return $this->fake
            ? 'This is a fake Deepgram transcript.'
            : $this->client()
                ->withBody($storage->get($path), $storage->mimeType($path))
                ->post('listen')
                ->json('results.channels.0.alternatives.0.transcript');
    }
}
