<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Plan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Plans/Index', [
            'plans' => Plan::all(),
        ]);
    }

    public function show(Plan $plan, Request $request)
    {
        return Inertia::render('Dashboard/Plans/Show', [
            'plan' => $plan,
            'intent' => $request->user()->createSetupIntent(),
        ]);
    }

    public function update(Plan $plan, SubscriptionRequest $request)
    {
        return rescue(function () use ($plan, $request) {
            /** @var User */
            $user = $request->user();
            $user->createOrGetStripeCustomer();
            $paymentMethod = $user->addPaymentMethod($request->validated('payment_method'));
            $user->newSubscription('default', $request->validated('price_id'))->create($paymentMethod->id);

            return redirect()->route('home')->with('message', 'You are now subscribed to the '.$plan->name.' plan!');
        }, function (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        });
    }
}
