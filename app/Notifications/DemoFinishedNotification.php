<?php

namespace App\Notifications;

use App\Models\Story;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemoFinishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Story $story
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Demo Story Finished')
            ->greeting('Hello!')
            ->line('Thank you for using Otto Story! We attached your result to this message.')
            ->attachData(
                Pdf::loadView('pdf.book', ['story' => $this->story])->output(),
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
