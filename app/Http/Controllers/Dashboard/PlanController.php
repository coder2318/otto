<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Cashier\PromotionCode;
use Stripe\StripeClient;
use Throwable;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Plans/Index', [
            'plans' => PlanResource::collection(Plan::all()),
        ]);
    }

    public function show(Plan $plan, Request $request)
    {
        return Inertia::render('Dashboard/Plans/Show', [
            'plan' => fn () => PlanResource::make($plan)->withPromo(Session::get('promo')),
            'intent' => fn () => $request->user()->createSetupIntent(),
        ]);
    }

    public function update(Plan $plan, SubscriptionRequest $request, StripeClient $stripe)
    {
        return rescue(function () use ($plan, $request, $stripe) {
            /** @var User */
            $user = $request->user();
            $user->createOrGetStripeCustomer([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->details?->phone,
            ]);
            $paymentMethod = $user->addPaymentMethod($request->validated('payment_method'));

            $price = $stripe->prices->retrieve($request->validated('price_id'));

            if ($price->recurring) {
                $subscription = $user->newSubscription('default', $request->validated('price_id'));
                if (Session::has('promo')) {
                    /** @var PromotionCode */
                    $promo = Session::get('promo');
                    // @phpstan-ignore-next-line
                    $subscription->withPromotionCode($promo->id);
                    Session::forget('promo');
                }
                // @phpstan-ignore-next-line
                $subscription->create($paymentMethod->id);
            } else {
                if (Session::has('promo')) {
                    /** @var PromotionCode */
                    $promo = Session::get('promo');
                    $user->invoicePrice($request->validated('price_id'), 1, [
                        // @phpstan-ignore-next-line
                        'discounts' => [['coupon' => $promo->coupon()->id]],
                    ]);
                    Session::forget('promo');
                } else {
                    $user->invoicePrice($request->validated('price_id'));
                }
            }

            $user->update(['plan_id' => $plan->id]);

            return redirect()->intended(route('dashboard.dashboard.index'))->with('message', 'You are now subscribed to the '.$plan->name.' plan!');
        }, function (Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        });
    }

    public function useCoupon(Request $request)
    {
        $request->validate([
            'coupon' => ['required', 'string'],
        ]);

        /** @var ?User */
        $user = $request->user();
        $promotionCode = $user?->findActivePromotionCode($request->coupon);

        if (! $promotionCode) {
            return redirect()->back()->with('error', 'Invalid coupon!');
        }

        Session::put('promo', $promotionCode);

        return redirect()->back()->with('message', 'Coupon applied successfully!');
    }
}
