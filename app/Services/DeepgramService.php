<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Storage;

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
            ? fake()->paragraph()
            : $this->client()->asJson()
                ->post('listen', compact('url'))
                ->json('results.channels.0.alternatives.0.transcript');
    }

    public function transcribeMedia(Media $media): ?string
    {
        if ('audio/webm' !== $mime = $media->getCustomProperty('mime-type', $media->mime_type)) {
            throw new Exception('mime-type must be audio/webm');
        }

        if ($media->hasCustomProperty('transcript')) {
            return $media->getCustomProperty('transcript');
        }

        $transcript = $this->fake
            ? fake()->paragraph()
            : $this->client()
                ->withBody(Storage::disk('s3')->get($media->getPath()), $mime)
                ->post('listen')
                ->json('results.channels.0.alternatives.0.transcript');

        $media->setCustomProperty('transcript', $transcript);
        $media->save();

        return $transcript;
    }
}
