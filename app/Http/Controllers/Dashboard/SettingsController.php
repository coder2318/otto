<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function updatePassword(Request $request, AuthService $service)
    {
        $service->update($request->user(), $request->all());

        return redirect()->back()->with('message', 'Your password has been updated!');
    }

    public function billing(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        return Inertia::render('Dashboard/Settings/Billing', [
            'current' => fn () => $user->subscription(),
            'period_end' => fn () => Carbon::createFromTimestamp(
                $user->subscription()->asStripeSubscription()->current_period_end
            ),
            'plans' => fn () => PlanResource::collection(Plan::all())
        ]);
    }

    public function updateBilling(Request $request)
    {
        return redirect()->back();
    }
}
