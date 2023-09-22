<?php

namespace App\Jobs;

use App\Models\Chapter;
use App\Notifications\ChapterEditReadyNotification;
use App\Services\OpenAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessChapter implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Chapter $chapter
    ) {
    }

    public function uniqueId(): string
    {
        return $this->chapter->id;
    }

    public function handle(OpenAIService $service): void
    {
        $this->chapter->edit = $service->chatEdit(
            $this->chapter->content,
            $this->chapter->title,
        );

        $this->chapter->processing = false;

        $this->chapter->save();

        $this->chapter->story->user->notify(new ChapterEditReadyNotification($this->chapter));
    }
}
