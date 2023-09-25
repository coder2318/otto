<?php

namespace App\Http\Controllers\Guests;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Guest;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Models\User;
use App\Notifications\GuestChapterInviteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ChapterController extends Controller
{
    public function invite(Story $story, TimelineQuestion $question, Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
        ]);

        $guest = Guest::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name']],
        );

        /** @var Chapter */
        $chapter = $story->chapters()->firstOrCreate(['timeline_question_id' => $question->id], [
            'title' => $question->question,
            'timeline_question_id' => $question->id,
            'timeline_id' => $question->timeline_id,
            'guest_id' => $guest->id,
            'status' => Status::DRAFT,
        ]);

        if ($cover = $question->cover) {
            /** @var Media $cover */
            $cover->copy($chapter, 'cover');
        }

        $guest->notify(new GuestChapterInviteNotification($chapter));

        return redirect()->back()->with('message', 'Guest invited successfully!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var Guest|User|null */
        $user = match ($type = $request->query('type', 'sent')) {
            'sent' => Auth::guard('web')->user(),
            'received' => Auth::guard('web-guest')->user(),
            default => abort(404),
        };

        if (! $user) {
            $type = $type === 'sent' ? 'received' : 'sent';

            return redirect()->route('guests.chapters.index', compact('type'));
        }

        return Inertia::render('Guests/Chapters/Index', [
            'chapters' => fn () => ChapterResource::collection(
                $user->chapters()
                    ->whereNotNull('guest_id')
                    ->with(['guest', 'user'])
                    ->select(['chapters.id', 'chapters.title', 'chapters.status', 'guest_id', 'story_id'])
                    ->paginate()
                    ->appends($request->query())
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        //
    }
}
