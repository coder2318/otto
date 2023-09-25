<?php

namespace App\Notifications;

use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class GuestChapterInviteNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Chapter $chapter,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->chapter->story->user;
        $name = $user->details->full_name ?? $user->details->first_name . ' ' . $user->details->last_name;

        return (new MailMessage)
            ->subject('Guest Chapter Invite')
            ->line("Dear, {$notifiable->name}! You have been invited to write a chapter on topic \"{$this->chapter->title}\" by {$name}. Please click the button below to accept the invitation.")
            ->action('Start Writing', URL::signedRoute('login.guests', ['guest' => $notifiable]));
    }
}
