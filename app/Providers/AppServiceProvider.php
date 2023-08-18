<?php

namespace App\Providers;

use App\Features\BetaAccess;
use Illuminate\Support\ServiceProvider;
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

        $this->features();
    }

    protected function features()
    {
        EnsureFeaturesAreActive::whenInactive(fn () => abort(403));

        $this->app->alias(BetaAccess::class, 'beta-access');
    }
}
