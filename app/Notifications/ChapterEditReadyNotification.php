<?php

namespace App\Notifications;

use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ChapterEditReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Chapter $chapter
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->chapter->id,
            'url' => route('dashboard.chapters.enhance', ['chapter' => $this->chapter->id]),
            'title' => 'Your chapter enhancement is ready!',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
