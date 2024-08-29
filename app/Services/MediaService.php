<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class MediaService
{
    public function __construct(
        protected DeepgramService $deepgram,
        protected TranslateService $translate,
    ) {
    }

    public function transcribe(Media &$media, ?string $source = null, ?string $target = null): ?string
    {
        if ($media->hasCustomProperty('transcript')) {
            return $media->getCustomProperty('transcript');
        }

        $transcript = rescue(fn () => match ($media->getCustomProperty('mime-type', $media->mime_type)) {
            'video/webm', 'audio/webm', 'audio/wav', 'audio/mpeg', 'audio/mpeg3', 'audio/mp3',
            'audio/x-mpeg-3', 'audio/m4a', 'audio/mp4', 'video/mp4', 'audio/flac', 'audio/aac',
            'audio/x-wav', 'audio/x-m4a' => $this->transcribeAudio($media, $source),
            'application/pdf' => $this->transcribePdf($media),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->transcribeDocx($media),
            'text/plain' => $this->transcribeText($media),
            default => null,
        });

        if (! $transcript) {
            Session::flash('error', 'Some files could not be transcribed.');

            return null;
        }

        if ($source) {
            $media->setCustomProperty('language', $target);
        }

        if ($target) {
            $media->setCustomProperty('translated', $target);
            $transcript = $this->translate->translate($transcript, [
                'format' => 'text',
                'target' => $target,
            ])['text'] ?? $transcript;
        }

        $media->setCustomProperty('transcript', $transcript)->save();

        return $transcript;
    }

    protected function format(string $text)
    {
        return collect(preg_split('/\n\n/', $text, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn (string $p) => preg_replace('/\s+/', ' ', $p))
            ->join(PHP_EOL.PHP_EOL);
    }

    protected function transcribeAudio(Media &$media, ?string $language = null): ?string
    {
        $language ??= $media->getCustomProperty('language');

        return $this->deepgram->transcribeFromFile(
            Storage::disk($media->disk),
            $media->getPath(),
            $language ? [
                'language' => $language,
                'paragraphs' => 'true',
                'punctuate' => 'true',
                'smart_format' => 'true',
            ] : null
        );
    }

    protected function transcribePdf(Media &$media): ?string
    {
        Storage::disk('local')->put(
            $path = 'temp/'.$media->getPath(),
            Storage::disk($media->disk)->get($media->getPath())
        );

        return tap(
            $this->format(Pdf::getText(Storage::disk('local')->path($path))),
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
