<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Story\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\QuizQuestionResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\UserResource;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Services\IsoService;
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

    public function edit(Request $request, IsoService $service)
    {
        return Inertia::render('Dashboard/Users/Edit', [
            'user' => fn () => UserResource::make($request->user()->load('avatar')),
            'countries' => fn () => $service->listCountries(),
            'languages' => fn () => $service->listLanguages(),
            'questions' => fn () => QuizQuestionResource::collection(QuizQuestion::all()),
        ]);
    }

    public function update(UpdateUserRequest $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        $user->update($request->validated());

        if ($request->hasFile('avatar')) {
            $user->avatar?->delete();
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return redirect()->back()->with('message', 'Personal information updated successfully.');
    }
}
