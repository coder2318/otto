<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status as ChapterStatus;
use App\Data\Story\Status as StoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\TranscribeRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\TimelineQuestionResource;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Models\User;
use App\Notifications\DemoFinishedNotification;
use App\Services\MediaService;
use App\Services\OpenAIService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DemoController extends Controller
{
    protected User $user;

    protected Story $story;

    protected Chapter $chapter;

    protected function data(Request $request, TimelineQuestion $question = null)
    {
        $this->user ??= $request->user();
        $this->story ??= $this->user->stories()->firstOrCreate(values: [
            'title' => 'Demo Story',
            'status' => StoryStatus::PENDING,
        ]);
        $this->chapter ??= $this->story->chapters()->firstOrCreate(values: [
            'title' => $question?->question ?? 'Demo Chapter',
            'status' => ChapterStatus::DRAFT,
            'timeline_question_id' => $question?->id,
            'timeline_id' => $question?->timeline_id,
        ]);

        return [$this->chapter, $this->story];
    }

    protected function hasStory(Request $request)
    {
        return $request->user()->stories()->count() > 0;
    }

    public function index()
    {
        return Inertia::render('Dashboard/Demo/Index', [
            'questions' => TimelineQuestionResource::collection(
                TimelineQuestion::inRandomOrder()->with('cover')->take(3)->get()
            ),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => ['required', 'exists:timeline_questions,id'],
        ]);

        $this->data($request, TimelineQuestion::find($request->input('question')));

        return redirect()->route('dashboard.demo.record');
    }

    public function record(Request $request)
    {
        [$chapter] = $this->data($request);

        if ($chapter->attachments()->count() > 0) {
            return redirect()->route('dashboard.demo.attachments')->with('status', 'You can not transcribe more than one record for demo!');
        }

        return Inertia::render('Dashboard/Demo/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function update(UpdateChapterRequest $request, MediaService $service)
    {
        /** @var Chapter */
        [$chapter] = $this->data($request);
        $chapter->update($request->validated());

        if ($request->hasFile('cover')) {
            $chapter->cover()->delete();
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        foreach ($request->validated('attachments') ?? [] as $attachment) {
            $record = $chapter->addMedia($attachment['file'])
                ->withCustomProperties(['mime-type' => $attachment['file']->getMimeType()] + ($attachment['options'] ?? []))
                ->toMediaCollection('attachments', 's3');

            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        if (isset($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }

        if ($redirect = $request->validated('redirect')) {
            return redirect()->route($redirect);
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }

    public function attachments(Request $request)
    {
        [$chapter] = $this->data($request);

        return Inertia::render('Dashboard/Demo/Attachments', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('attachments')
            ),
        ]);
    }

    public function transcribe(TranscribeRequest $request, MediaService $service)
    {
        [$chapter] = $this->data($request);

        /** @var \Illuminate\Support\Collection<int,\Spatie\MediaLibrary\MediaCollections\Models\Media> */
        $media = $chapter->attachments()->whereIn('id', $request->validated('attachments'))->get();

        $transcriptions = null;

        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('dashboard.demo.write')->with('transcriptions', $transcriptions);
    }

    public function write(Request $request)
    {
        [$chapter] = $this->data($request);

        return Inertia::render('Dashboard/Demo/Write', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'transcriptions' => fn () => session('transcriptions'),
        ]);
    }

    public function deleteAttachments(Media $attachment)
    {
        $attachment->delete();

        return redirect()->route('dashboard.demo.attachments')->with('message', 'Attachment deleted successfully!');
    }

    public function enhance(Request $request, OpenAIService $service)
    {
        if (! $request->user()->enhances) {
            return redirect()->route('dashboard.demo.write')->with('status', 'You can only enhance once for demo!');
        }

        [$chapter] = $this->data($request);

        if (! $chapter->content) {
            return redirect()->back()->with('status', 'Chapter content is empty!');
        }

        $request->user()->decrement('enhances', 1);

        return Inertia::render('Dashboard/Demo/Enhance', [
            'chapter' => fn () => ChapterResource::make($chapter->load('cover')),
            'otto_edit' => $service->chatEdit(
                $chapter->content,
                $chapter->question,
                $request->user()?->details
            ),
        ]);
    }

    public function finish(Request $request)
    {
        [$chapter, $story] = $this->data($request);

        if ($story->status !== StoryStatus::PUBLISHED) {
            $request->user()->notify(new DemoFinishedNotification($story));
        }

        $story->update([
            'status' => StoryStatus::PUBLISHED,
        ]);

        $chapter->update([
            'status' => ChapterStatus::PUBLISHED,
        ]);

        return Inertia::render('Dashboard/Demo/Finish', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function book(Request $request)
    {
        $story = $request->user()->stories()->first();

        abort_if(! $story, 404);

        $chapters = $story->chapters()->orderBy('timeline_id', 'asc')->orderBy('order', 'asc')->lazy();

        return Pdf::loadView('pdf.book', compact('story', 'chapters'))->stream('demo.pdf');
    }
}
