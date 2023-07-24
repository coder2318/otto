<?php

namespace App\Http\Controllers;

use App\Data\User\Details;
use App\Http\Requests\UserDetailsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function quickstart(Request $request)
    {
        return Inertia::render('Quickstart', [
            'details' => $request->user()->details
        ]);
    }

    public function saveProfileDetails(UserDetailsRequest $request)
    {
        $request->user()->update([
            'details' => $request->details($request->user()->details),
        ]);

        return redirect()->back();
    }
}
