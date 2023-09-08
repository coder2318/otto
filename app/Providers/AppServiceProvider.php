<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
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

        EnsureFeaturesAreActive::whenInactive(
            fn (Request $request) => Inertia::render('Pennent', [
                'error' => trans("I'm a teapot"),
                'description' => trans('The page you are looking for is available only to a limited number of testers.'.PHP_EOL.'Sign up for the pre-order to request the access!'),
            ])->toResponse($request)
        );
    }
}
