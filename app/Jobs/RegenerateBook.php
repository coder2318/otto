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

        $pdf = Pdf::loadView('pdf.book', ['story' => $this->story, 'chapters' => $chapters]);
        /** @var Mpdf */
        $mpdf = $pdf->getMpdf();
        $mpdf->curlAllowUnsafeSslRequests = true;
        $pdf->save($path = "/tmp/book-{$this->story->id}.pdf");

        $this->story->clearMediaCollection('book');

        $this->story->addMedia($path)
            ->withCustomProperties(['pages' => $mpdf->page])
            ->toMediaCollection('book', config('media-library.private_disk_name'));
    }
}
