<?php

namespace App\Notifications;

use App\Http\Controllers\FontController;
use App\Models\Story;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;

class DemoFinishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Story $story
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $config = FontController::getConfig($this->story->font);

        return (new MailMessage)
            ->subject('Demo Story Finished')
            ->greeting('Hello!')
            ->line('Thank you for using Otto Story! We attached your result to this message.')
            ->attachData(
                Pdf::loadView(
                    'pdf.book',
                    [
                        'story' => $this->story,
                        'chapters' => $this->story->chapters()->orderBy('timeline_id', 'asc')->orderBy('order', 'asc')->lazy(),
                        'fontName' => $config['default_font']
                    ],
                    [],
                    $config
                )->output(),
                'demo.pdf',
                ['mime' => 'application/pdf']
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
