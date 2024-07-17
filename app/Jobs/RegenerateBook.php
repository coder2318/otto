<?php

namespace App\Jobs;

use App\Data\Chapter\Status;
use App\Models\Story;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;
use Mpdf\Mpdf;

class RegenerateBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Story $story
    ) {
    }

    public function handle(): void
    {
        $chapters = $this->story->chapters()
            ->with('images')
            ->where('status', Status::PUBLISHED)
            ->orderBy('timeline_id', 'asc')
            ->orderBy('order', 'asc')
            ->lazy();

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
                if (! isset($chapterImagesById[$image->id])) { // @phpstan-ignore-line
                    $image->delete();
                }
            }
        }

        $pdf = Pdf::loadView('pdf.book', ['story' => $this->story, 'chapters' => $chapters]);
        /** @var Mpdf */
        $mpdf = $pdf->getMpdf();
        $mpdf->curlAllowUnsafeSslRequests = true;
        $pdf->save($path = "/tmp/book-{$this->story->id}.pdf");

        $this->story->clearMediaCollection('book');

        $size = config('media-library.max_file_size');
        config(['media-library.max_file_size' => INF]);

        $this->story->addMedia($path)
            ->withCustomProperties(['pages' => $mpdf->page])
            ->toMediaCollection('book', config('media-library.private_disk_name'));

        config(['media-library.max_file_size' => $size]);

        dispatch(new RegenerateBookCover($this->story));
    }
}
