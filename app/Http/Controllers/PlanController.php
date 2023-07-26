<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Plans/Index', [
            'plans' => Plan::all(),
        ]);
    }

    public function show(Plan $plan, Request $request)
    {
        return Inertia::render('Plans/Show', [
            'plan' => $plan,
            'intent' => $request->user()->createSetupIntent(),
        ]);
    }

    public function update(Plan $plan, Request $request)
    {
        /** @var User */
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        $paymentMethod = $user->addPaymentMethod($request->payment_method);

        $user->newSubscription($plan->name, $plan->stripe_plan)->create($paymentMethod?->id);

        return redirect()->route('home')->with('success', 'You are now subscribed to the '.$plan->name.' plan!');
    }
}
