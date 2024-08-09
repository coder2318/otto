<?php

namespace App\Jobs;

use App\Data\Chapter\Status;
use App\Models\Story;
use App\Http\Resources\GuestResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;
use Mpdf\Mpdf;

class RegenerateBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    public $cacheKeyPattern = 'book-%s';

    public $mediaQuality = '';

    public $mediaCollectionName = 'book';

    public $dispatchRegenerateBook = false;

    public $dispatchRegenerateBookCover = false;

    public function __construct(
        public Story $story
    ) {}

    protected function getVersion()
    {
        $version = Redis::get(sprintf($this->cacheKeyPattern, $this->story->id), 0);

        return $version;
    }

    protected function increaseVersion()
    {
        $version = $this->getVersion();
        $version++;

        Redis::set(sprintf($this->cacheKeyPattern, $this->story->id), $version);

        return $version;
    }

    public function handle(): void
    {
        $currentVersion = $this->increaseVersion();
        $imageErrors = [];
        $imagesById = [];
        $fontName = $this->story->font;
        $fontPath = config('story.book_fonts_dir') . '/' . $fontName;
        $fontData = $this->getFontData('default_font', $fontPath);
        $config = [
            'custom_font_dir' => $fontPath,
            'custom_font_data' => $fontData
        ];

        $chapters = $this->story->chapters()
            ->with('images', 'guest')
            ->where('status', Status::PUBLISHED)
            ->orderBy('timeline_id', 'asc')
            ->orderBy('order', 'asc')
            ->lazy();

        $chaptersWithGuestAvatars = [];

        foreach ($chapters as $chapter) {
            $chapterImagesById = [];

            preg_replace_callback('/<img[^>]+>/im', function ($matches) use (&$chapterImagesById) {
                $imageTag = $matches[0];

                preg_match('@id="([^"]+)"@', $imageTag, $match);
                $id = array_pop($match);

                $chapterImagesById[$id] = $id;

                return $imageTag;
            }, $chapter->content);

            foreach ($chapter->images as $image) {
                $imageId = $image->id; // @phpstan-ignore-line

                if (!isset($chapterImagesById[$imageId])) {
                    $image->delete();

                    continue;
                }

                $exists = Storage::disk($image->disk)->exists($image->getPath()); // @phpstan-ignore-line

                if (!$exists) {
                    $url = url("/chapters/{$chapter->id}/write");
                    $imageErrors[] = $url;
                } else {
                    $imagesById[$imageId] = [
                        'id' => $imageId,
                        'url' => $image->getTemporaryUrl(now()->addHour(), $this->mediaQuality),
                        'caption' => $image->getCustomProperty('caption'),
                    ];
                }
            }

            if ($chapter->guest) {
                $chapter->guest = GuestResource::make($chapter->guest)->processAvatar();
            }

            $chaptersWithGuestAvatars[] = $chapter;
        }

        if ($currentVersion != $this->getVersion()) {
            $this->delete();

            return;
        }

        $path = '/tmp/' . sprintf($this->cacheKeyPattern, $this->story->id) . '-' . md5(microtime(true)) . '.pdf';

        $pdf = Pdf::loadView(
            'pdf.book',
            [
                'story' => $this->story,
                'chapters' => $chaptersWithGuestAvatars,
                'imagesById' => $imagesById,
                'imageErrors' => $imageErrors,
                'fontName' => 'default_font'
            ],
            [],
            $config
        );

        /** @var Mpdf */
        $mpdf = $pdf->getMpdf();
        $mpdf->curlAllowUnsafeSslRequests = true;
        $pdf->save($path);

        if ($currentVersion != $this->getVersion()) {
            if (file_exists($path)) {
                unlink($path);
            }

            $this->delete();

            return;
        }

        $this->story->clearMediaCollection($this->mediaCollectionName);

        $size = config('media-library.max_file_size');
        config(['media-library.max_file_size' => INF]);

        $this->story->addMedia($path)
            ->withCustomProperties(['pages' => $mpdf->page])
            ->toMediaCollection($this->mediaCollectionName, config('media-library.private_disk_name'));

        config(['media-library.max_file_size' => $size]);

        if ($currentVersion != $this->getVersion()) {
            $this->delete();

            return;
        }

        if ($this->dispatchRegenerateBook) {
            dispatch(new RegenerateBook($this->story));
        }

        if ($this->dispatchRegenerateBookCover) {
            dispatch(new RegenerateBookCover($this->story));
        }
    }

    private function getFontData(string $fontName, string $fontPath): array
    {
        $fontData = [];

        try {
            $files = File::files($fontPath);

            foreach ($files as $file) {
                $filename = $file->getFilename();

                if (stripos($filename, 'regular') !== false) {
                    $fontData[$fontName]['R'] = $filename;
                } elseif (stripos($filename, 'bolditalic') !== false) {
                    $fontData[$fontName]['BI'] = $filename;
                } elseif (stripos($filename, 'bold') !== false) {
                    $fontData[$fontName]['B'] = $filename;
                } elseif (stripos($filename, 'italic') !== false) {
                    $fontData[$fontName]['I'] = $filename;
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to retrieve font data at {$fontPath}: " . $e->getMessage());
            return [];
        }

        return $fontData;
    }
}
