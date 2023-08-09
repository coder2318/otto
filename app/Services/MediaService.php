<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\PdfToText\Pdf;

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

        $transcript = rescue(fn () => match ($media->getCustomProperty('mime-type', $media->mime_type)) {
            'audio/webm', 'video/webm', 'audio/x-wav', 'audio/mpeg' => $this->transcribeAudio($media),
            'application/pdf' => $this->transcribePdf($media),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->transcribeDocx($media),
            'text/plain' => $this->transcribeText($media),
            default => null,
        });

        $transcript
            ? $media->setCustomProperty('transcript', $transcript)->save()
            : Session::flash('error', 'Some files could not be transcribed.');

        return $transcript;
    }

    protected function transcribeAudio(Media &$media): ?string
    {
        return $this->deepgram->transcribeFromFile(Storage::disk($media->disk), $media->getPath());
    }

    protected function transcribePdf(Media &$media): ?string
    {
        Storage::disk('local')->put(
            $path = 'temp/'.$media->getPath(),
            Storage::disk($media->disk)->get($media->getPath())
        );

        return tap(
            Pdf::getText(Storage::disk('local')->path($path)),
            fn () => Storage::disk('local')->delete($path)
        );
    }

    protected function transcribeDocx(Media &$media): ?string
    {
        Storage::disk('local')->put(
            $path = 'temp/'.$media->getPath(),
            Storage::disk($media->disk)->get($media->getPath())
        );

        $content = null;
        $zip = new \ZipArchive;

        if ($zip->open(Storage::disk('local')->path($path)) && $index = $zip->locateName('word/document.xml')) {
            $content = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $zip->getFromIndex($index));
            $content = strip_tags(str_replace('</w:r></w:p>', "\r\n", $content));
        }

        $zip->close();

        Storage::disk('local')->delete($path);

        return $content;
    }

    protected function transcribeText(Media &$media): ?string
    {
        Storage::disk('local')->put(
            $path = 'temp/'.$media->getPath(),
            Storage::disk($media->disk)->get($media->getPath())
        );

        $content = file_get_contents(Storage::disk('local')->path($path));

        Storage::disk('local')->delete($path);

        return $content;
    }
}
