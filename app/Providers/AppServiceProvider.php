<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Sqids\Sqids;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(StripeClient::class, fn () => new StripeClient(config('cashier.secret')));

        $this->app->bind(Sqids::class, fn () => new Sqids(config('services.sqids.dictionary')));

        EnsureFeaturesAreActive::whenInactive(fn (Request $request) => $request->wantsJson()
            ? response()->json(['message' => 'This feature is not available'], 418)
            : Inertia::render('Pennent')
        );
    }
}
