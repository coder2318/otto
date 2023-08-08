<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    public function __construct(
        protected DeepgramService $deepgram,
    ) {
    }

    public function transcribe(Media &$media): ?string
    {
        if ($media->hasCustomProperty('transcript')) {
            return $media->getCustomProperty('transcript');
        }

        $transcript = match ($media->getCustomProperty('mime-type', $media->mime_type)) {
            'audio/webm', 'video/webm', 'audio/x-wav', 'audio/mpeg' => $this->transcribeAudio($media),
            default => Session::flash('error', 'Some files was not transcribed!'),
        };

        if ($transcript) {
            $media->setCustomProperty('transcript', $transcript)->save();
        }

        return $transcript;
    }

    protected function transcribeAudio(Media &$media): string
    {
        return $this->deepgram->transcribeFromFile(Storage::disk($media->disk), $media->getPath());
    }
}
