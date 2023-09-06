<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show(User $user)
    {
        return Inertia::render('Dashboard/Users/Show', compact('user'));
    }

    public function edit(Request $request)
    {
        return Inertia::render('Dashboard/Users/Edit', [
            'user' => fn () => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        $user->update($request->all());

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }
}
