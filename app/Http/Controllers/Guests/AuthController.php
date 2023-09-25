<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke(Guest $guest)
    {
        Auth::guard('web-guest')->login($guest);

        return redirect()->route('guests.chapters.index');
    }
}
