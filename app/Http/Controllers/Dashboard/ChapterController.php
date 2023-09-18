<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\ChaptersRequest;
use App\Http\Requests\Chapters\StoreChapterRequest;
use App\Http\Requests\Chapters\TranscribeRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineQuestionResource;
use App\Http\Resources\TimelineResource;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\Timeline;
use App\Models\TimelineQuestion;
use App\Services\MediaService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Chapter::class, 'chapter');
        $this->middleware('can:update,chapter')
            ->only(['write', 'attachments', 'transcribe', 'deleteAttachments', 'record', 'upload', 'enhance', 'finish']);
    }

    public function index(Story $story, ChaptersRequest $request)
    {
        return Inertia::render('Dashboard/Chapters/Index', [
            'story' => fn () => StoryResource::make($story->load('cover')),
            'chapters' => fn () => ChapterResource::collection(
                $request->chapters($story->chapters()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
            'timelines' => fn () => TimelineResource::collection(
                Timeline::all(['id', 'title'])
            ),
        ]);
    }

    public function write(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Write', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'transcriptions' => fn () => session('transcriptions'),
        ]);
    }

    public function attachments(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Attachments', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('attachments')
            ),
        ]);
    }

    public function transcribe(Chapter $chapter, TranscribeRequest $request, MediaService $service)
    {
        /** @var \Illuminate\Support\Collection<int,\Spatie\MediaLibrary\MediaCollections\Models\Media> */
        $media = $chapter->attachments()->whereIn('id', $request->validated('attachments'))->get();

        $transcriptions = null;

        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('dashboard.chapters.write', compact('chapter'))->with('transcriptions', $transcriptions);
    }

    public function deleteAttachments(Chapter $chapter, Media $attachment)
    {
        $attachment->delete();

        return redirect()->route('dashboard.chapters.attachments', compact('chapter'))->with('message', 'Attachment deleted successfully!');
    }

    public function record(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function upload(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Upload', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function enhance(Chapter $chapter, Request $request, OpenAIService $service)
    {
        if (! $chapter->content) {
            return redirect()->back()->with('status', 'Chapter content is empty!');
        }

        return Inertia::render('Dashboard/Chapters/Enhance', [
            'chapter' => fn () => ChapterResource::make($chapter->load('cover')),
            'otto_edit' => $service->chatEdit(
                $chapter->content,
                $chapter->question,
                $request->user()?->details
            ),
        ]);
    }

    public function finish(Chapter $chapter)
    {
        $chapter->update([
            'status' => Status::PUBLISHED,
        ]);

        return Inertia::render('Dashboard/Chapters/Finish', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'questions' => fn () => TimelineQuestionResource::collection((
                $chapter?->timeline->questions()->where('id', '!=', $chapter?->timeline_question_id) ?? // @phpstan-ignore-line
                TimelineQuestion::query()
            )->inRandomOrder()->limit(3)->get()),
        ]);
    }

    public function create(Story $story)
    {
        return Inertia::render('Dashboard/Chapters/Create', [
            'timelines' => fn () => TimelineResource::collection(Timeline::all(['id', 'title'])),
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function store(Story $story, StoreChapterRequest $request)
    {
        /** @var Chapter */
        $chapter = $story->chapters()->create($request->validated() + [
            'status' => Status::DRAFT,
        ]);

        if ($request->hasFile('cover')) {
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return redirect()->route('dashboard.chapters.edit', compact('story', 'chapter'))->with('message', 'Chapter created successfully!');
    }

    public function show(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Show', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function edit(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Edit', [
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
                ->toMediaCollection('attachments', 's3');

            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        if (isset($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }

        if ($redirect = $request->validated('redirect')) {
            return redirect()->route($redirect, compact('chapter'));
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return redirect()->route('dashboard.stories.chapters.index', ['story' => $chapter->story_id])->with('message', 'Chapter deleted successfully!');
    }
}
