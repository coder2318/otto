<?php

namespace App\Notifications\Notifiables;

use Illuminate\Notifications\AnonymousNotifiable;

class AdminNotifiable extends AnonymousNotifiable
{
    public function routeNotificationFor($driver)
    {
        return match ($driver) {
            'mail' => config('services.admin.email'),
            default => parent::routeNotificationFor($driver),
        };
    }
}
