<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            abort_unless(in_array($request->route('provider'), [
                'facebook',
                'google',
            ]), 404);

            return $next($request);
        });
    }

    public function login(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function redirect(string $provider)
    {
        $user = Socialite::driver($provider)->user();

        $user = User::firstOrCreate([
            'email' => $user->email,
        ], [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'avatar' => $user->getAvatar(),
            'password' => Hash::make(Str::random()),
        ]);

        Auth::login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
