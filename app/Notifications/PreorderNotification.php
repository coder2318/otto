<?php

namespace App\Notifications;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PreorderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Preorder $preorder,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->markdown('mail.preorder', [
            'name' => $this->preorder->name,
            'email' => $this->preorder->email,
        ])->subject('New preorder request');
    }
}
