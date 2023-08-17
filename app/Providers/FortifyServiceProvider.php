<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Spatie\Honeypot\Honeypot;

class FortifyServiceProvider extends ServiceProvider
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
        $honeypot = ['honeypot' => fn () => app(Honeypot::class)];
        // Features
        Fortify::createUsersUsing(AuthService::class);
        Fortify::updateUserProfileInformationUsing(AuthService::class);
        Fortify::updateUserPasswordsUsing(AuthService::class);
        Fortify::resetUserPasswordsUsing(AuthService::class);

        // Views
        Fortify::loginView(fn () => Inertia::render('Auth/Login', $honeypot));
        Fortify::registerView(fn () => Inertia::render('Auth/Register', $honeypot));
        Fortify::verifyEmailView(fn () => Inertia::render('Auth/VerifyEmail', $honeypot));
        Fortify::requestPasswordResetLinkView(fn () => Inertia::render('Auth/ForgotPassword', $honeypot));
        Fortify::resetPasswordView(fn () => Inertia::render('Auth/ResetPassword', $honeypot));
        Fortify::confirmPasswordView(fn () => Inertia::render('Auth/ConfirmPassword', $honeypot));
        //Fortify::twoFactorChallengeView(fn () => Inertia::render('Auth/TwoFactorChallenge'));

        // Rate Limiting
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by(
            Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip())
        ));

        // RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by(
        //     $request->session()->get('login.id'))
        // );
    }
}
