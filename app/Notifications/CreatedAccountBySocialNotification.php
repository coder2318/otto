<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreatedAccountBySocialNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $password,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to the OTTO Story!')
            ->markdown('mail.account-created', [
                'password' => $this->password,
            ]);
    }
}
