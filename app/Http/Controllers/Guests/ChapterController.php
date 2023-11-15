<?php

namespace App\Http\Controllers\Guests;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\TranscribeRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Guest;
use App\Models\Media;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Models\User;
use App\Notifications\GuestChapterInviteNotification;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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
            'status' => Status::UNDONE,
        ]);

        if ($cover = $question->cover) {
            /** @var Media $cover */
            $cover->copy($chapter, 'cover');
        }

        $guest->notify(new GuestChapterInviteNotification($chapter));

        return redirect()->back()->with('message', 'Guest invited successfully!');
    }

    public function resend(Chapter $chapter)
    {
        $chapter->guest->notify(new GuestChapterInviteNotification($chapter));

        return redirect()->back()->with('message', 'Guest reinvited successfully!');
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
            return redirect()->route('guests.chapters.index', [
                'type' => $type === 'sent' ? 'received' : 'sent',
            ]);
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

    public function write(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Write', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'transcriptions' => fn () => session('transcriptions'),
        ]);
    }

    public function attachments(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Attachments', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('attachments')
            ),
        ]);
    }

    public function transcribe(Chapter $chapter, TranscribeRequest $request, MediaService $service)
    {
        /** @var \Illuminate\Support\Collection<int,\App\Models\Media> */
        $media = $chapter->attachments()->whereIn('id', $request->validated('attachments'))->get();

        $transcriptions = null;

        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('guests.chapters.write', compact('chapter'))->with('transcriptions', $transcriptions);
    }

    public function deleteAttachments(Chapter $chapter, Media $attachment)
    {
        $attachment->delete();

        return redirect()->route('guests.chapters.attachments', compact('chapter'))->with('message', 'Attachment deleted successfully!');
    }

    public function record(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function upload(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Upload', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function show(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Show', [
            'chapter' => fn () => ChapterResource::make($chapter->load('guest.avatar')),
        ]);
    }

    public function edit(Chapter $chapter)
    {
        return Inertia::render('Guests/Chapters/Edit', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function finish(Chapter $chapter)
    {
        $chapter->update([
            'status' => Status::PUBLISHED,
        ]);

        return Inertia::render('Guests/Chapters/Finish', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function update(UpdateChapterRequest $request, Chapter $chapter, MediaService $service)
    {
        $chapter->update($request->validated());

        if ($request->hasFile('cover')) {
            $chapter->cover()->delete();
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        foreach ($request->validated('attachments') ?? [] as $attachment) {
            $record = $chapter->addMedia($attachment['file'])
                ->withCustomProperties(['mime-type' => $attachment['file']->getMimeType()] + ($attachment['options'] ?? []))
                ->toMediaCollection('attachments', config('media-library.private_disk_name'));

            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        if (isset($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }

        if ($redirect = $request->validated('redirect')) {
            $story = $chapter->story;

            return redirect()->route($redirect, compact('chapter', 'story'))->with('message', 'Chapter updated successfully!');
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }
}
