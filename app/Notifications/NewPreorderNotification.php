<?php

namespace App\Notifications;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPreorderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Preorder $preorder
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Preorder')
            ->greeting('Hello!')
            ->line('I just made a new preorder on your platform!')
            ->line('Here is the information:')
            ->line('Name: '.$this->preorder->name)
            ->line('Email: '.$this->preorder->email);
    }
}
