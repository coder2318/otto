<?php

namespace App\Http\Controllers\Guests;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Guest;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Notifications\GuestChapterInviteNotification;
use Illuminate\Http\Request;
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
    public function index()
    {
        return Inertia::render('Guests/Chapters/Index');
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
