<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function setup()
    {
        return Inertia::render('Guests/Setup');
    }

    public function postSetup(Request $request)
    {
        $data = $request->validate([
            'avatar' => ['sometimes', 'nullable', 'image'],
            'relationship' => ['required', 'string', 'max:255'],
        ]);

        /** @var Guest */
        $user = $request->user('web-guest');

        if ($avatar = $data['avatar'] ?? null) {
            $user->addMedia($avatar)->toMediaCollection('avatar');
            unset($data['avatar']);
        }

        $user->update(['details' => $data]);

        return redirect()->route('guests.chapters.index')->with('message', 'Your profile has been updated!');
    }
}
