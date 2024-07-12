<?php

namespace App\Jobs;

use App\Models\Story;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;
use Mpdf\Mpdf;

class RegenerateBookCover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Story $story
    ) {
    }

    public function handle(): void
    {
        if (! $cover = $this->story->cover) {
            return;
        }

        $spine = $this->spineWidth($this->story->book->getCustomProperty('pages')) * 25.4; // @phpstan-ignore-line

        $pdf = Pdf::loadView('pdf.book-cover', [
            'cover' => $cover,
            'width' => (2 * 178.181 + $spine).'mm',
            'height' => '278mm',
        ]);
        /** @var Mpdf */
        $mpdf = $pdf->getMpdf();
        $mpdf->curlAllowUnsafeSslRequests = true;
        $pdf->save($path = "/tmp/book-cover-{$this->story->id}.pdf");

        $this->story->clearMediaCollection('book-cover');

        $this->story->addMedia($path)
            ->withCustomProperties(compact('spine'))
            ->toMediaCollection('book-cover', config('media-library.private_disk_name'));
    }

    protected function spineWidth(int $pages): float
    {
        return match (true) {
            $pages < 24 => throw new \Exception('Book must have at least 24 pages.'),
            $pages < 85 => 0.25,
            $pages < 141 => 0.5,
            $pages < 169 => 0.625,
            $pages < 195 => 0.688,
            $pages < 223 => 0.75,
            $pages < 251 => 0.813,
            $pages < 279 => 0.875,
            $pages < 307 => 0.938,
            $pages < 335 => 1,
            $pages < 361 => 1.063,
            $pages < 389 => 1.125,
            $pages < 417 => 1.188,
            $pages < 445 => 1.25,
            $pages < 473 => 1.313,
            $pages < 501 => 1.375,
            $pages < 529 => 1.438,
            $pages < 557 => 1.5,
            $pages < 583 => 1.563,
            $pages < 611 => 1.625,
            $pages < 639 => 1.688,
            $pages < 667 => 1.75,
            $pages < 695 => 1.813,
            $pages < 723 => 1.875,
            $pages < 751 => 1.938,
            $pages < 779 => 2,
            default => 2.12,
        };
    }
}
