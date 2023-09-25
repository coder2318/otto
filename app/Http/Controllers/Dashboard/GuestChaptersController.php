<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Guest;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Notifications\GuestChapterInviteNotification;
use Illuminate\Http\Request;

class GuestChaptersController extends Controller
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

        $guest->notify(new GuestChapterInviteNotification($chapter));

        return redirect()->back()->with('message', 'Guest invited successfully!');
    }
}
