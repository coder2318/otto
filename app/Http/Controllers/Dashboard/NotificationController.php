<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function read(Request $request, ?DatabaseNotification $notification = null)
    {
        $notification
            ? $notification->markAsRead()
            : $request->user()->unreadNotifications()->update(['read_at' => now()]);
    }
}
