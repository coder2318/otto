<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\AuthService;
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
            'current_plan' => fn () => $user->plan,
            'invoices' => fn () => $user->invoices(),
            'upcoming' => fn () => $user->subscription()?->upcomingInvoice(),
            'plans' => fn () => PlanResource::collection(Plan::all()),
        ]);
    }

    public function postBilling(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        abort_unless((bool) $subscription = $user->subscription(), 404);

        $subscription->ends_at
            ? $subscription->resume()
            : $subscription->cancel();

        return redirect()->back()->with('message', 'Your subscription has been updated!');
    }

    public function putBilling(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        abort_unless((bool) $subscription = $user->subscription(), 404);

        $subscription->swapAndInvoice($request->price);

        return redirect()->back()->with('message', 'Your subscription has been updated!');
    }
}
