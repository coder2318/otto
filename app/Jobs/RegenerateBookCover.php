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

        $spine = $this->spineWidth($this->story->book->getCustomProperty('pages')); // @phpstan-ignore-line

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
        if ($pages < 24) {
            return 0.25 * 25.4;
        }

        $stops = [
            24 => 0.25,
            85 => 0.5,
            141 => 0.625,
            169 => 0.688,
            195 => 0.75,
            223 => 0.813,
            251 => 0.875,
            279 => 0.938,
            307 => 1,
            335 => 1.063,
            361 => 1.125,
            389 => 1.188,
            417 => 1.25,
            445 => 1.313,
            473 => 1.375,
            501 => 1.438,
            529 => 1.5,
            557 => 1.563,
            583 => 1.625,
            611 => 1.688,
            639 => 1.75,
            667 => 1.813,
            695 => 1.875,
            723 => 1.938,
            751 => 2,
            779 => 2.063,
            800 => 2.12,
        ];

        foreach ($stops as $_pages => $width) {
            if ($pages > $_pages) {
                return $width * 25.4;
            }
        }

        return 2.12 * 25.4;
    }
}
