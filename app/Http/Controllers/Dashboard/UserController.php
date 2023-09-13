<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Story\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show(User $user)
    {
        return Inertia::render('Dashboard/Users/Show', [
            'user' => fn () => UserResource::make($user->load('avatar')),
            'stories' => fn () => StoryResource::collection(
                $user->stories()->where('status', Status::PUBLISHED)->with('cover')->get()
            ),
        ]);
    }

    public function edit(Request $request)
    {
        return Inertia::render('Dashboard/Users/Edit', [
            'user' => fn () => UserResource::make($request->user()->load('avatar')),
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        $user->update($request->all());

        if ($request->hasFile('avatar')) {
            $user->avatar?->delete();
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return redirect()->back()->with('message', 'Personal information updated successfully.');
    }
}
