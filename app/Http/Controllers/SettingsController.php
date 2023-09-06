<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function notifications()
    {
        return Inertia::render('Dashboard/Settings/Notifications');
    }

    public function updateNotifications(Request $request)
    {
        return redirect()->back();
    }

    public function integrations()
    {
        return Inertia::render('Dashboard/Settings/Integrations');
    }

    public function updateIntegrations(Request $request)
    {
        return redirect()->back();
    }

    public function password()
    {
        return Inertia::render('Dashboard/Settings/Password');
    }

    public function updatePassword(Request $request)
    {
        return redirect()->back();
    }

    public function billing()
    {
        return Inertia::render('Dashboard/Settings/Billing');
    }

    public function updateBilling(Request $request)
    {
        return redirect()->back();
    }
}
