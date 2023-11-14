<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CreatedAccountBySocialNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected array $providers = [
        'facebook',
        'google',
    ];

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            abort_unless(in_array($request->route('provider'), $this->providers), 404);

            return $next($request);
        });
    }

    public function login(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function redirect(string $provider)
    {
        $userData = Socialite::driver($provider)->user();

        if (! $user = User::where('email', $email = $userData->getEmail())->first()) {
            /** @var User */
            $user = User::create([
                'name' => $userData->getNickname(),
                'email' => $email,
                'avatar' => $userData->getAvatar(),
                'password' => Hash::make($password = Str::random()),
            ]);

            $user->notify(new CreatedAccountBySocialNotification($password));
        }

        /** @var User $user */
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
