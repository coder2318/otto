<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuickstartRequest;
use App\Http\Resources\QuizQuestionResource;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuickstartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->details) {
            return redirect()->route('demo');
        }

        return Inertia::render('Dashboard/Quickstart', [
            'details' => $request->user()->details,
            'questions' => QuizQuestionResource::collection(
                QuizQuestion::orderBy('order')->get()
            ),
        ]);
    }

    public function store(QuickstartRequest $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        $user->details = $request->details($user->details);

        $user->save();

        return redirect()->route('demo');
    }
}
