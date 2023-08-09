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
use App\Http\Resources\TimelineResource;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\Timeline;
use App\Services\MediaService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Chapter::class, 'chapter');
    }

    /**
     * Display a listing of the resource.
     */
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
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Write', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'transcriptions' => fn () => session('transcriptions'),
        ]);
    }

    public function attachments(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Attachments', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('attachments')
            ),
        ]);
    }

    public function transcribe(Chapter $chapter, TranscribeRequest $request, MediaService $service)
    {
        $this->authorize('update', $chapter);

        /** @var \Illuminate\Support\Collection<int,\Spatie\MediaLibrary\MediaCollections\Models\Media> */
        $media = $chapter->attachments()->whereIn('id', $request->validated('attachments'))->get();

        $transcriptions = null;

        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('chapters.write', compact('chapter'))->with('transcriptions', $transcriptions);
    }

    public function deleteAttachments(Chapter $chapter, int $attachment)
    {
        abort_unless((bool) $attachment = $chapter->attachments()->find($attachment), 404);

        $this->authorize('update', $chapter);

        $attachment->delete();

        return redirect()->route('chapters.attachments', compact('chapter'))->with('message', 'Attachment deleted successfully!');
    }

    public function record(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function upload(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Upload', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function enchance(Chapter $chapter, Request $request, OpenAIService $service)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Enchance', [
            'chapter' => fn () => ChapterResource::make($chapter->load('cover')),
            'otto_edit' => $service->edit(
                $chapter->content,
                $service->createInstractions($request->user()?->details)
            ),
        ]);
    }

    public function finish(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        $chapter->update([
            'status' => Status::PUBLISHED,
        ]);

        return Inertia::render('Dashboard/Chapters/Finish', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Story $story)
    {
        return Inertia::render('Dashboard/Chapters/Create', [
            'timelines' => fn () => TimelineResource::collection(Timeline::all(['id', 'title'])),
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Story $story, StoreChapterRequest $request)
    {
        /** @var Chapter */
        $chapter = $story->chapters()->create($request->validated() + [
            'status' => Status::DRAFT,
        ]);

        if ($request->hasFile('cover')) {
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return redirect()->route('chapters.edit', compact('story', 'chapter'))->with('message', 'Chapter created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Show', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Edit', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
        $chapter->update($request->validated());

        if ($request->hasFile('cover')) {
            $chapter->cover()->delete();
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        /** @var UploadedFile */
        foreach ($request->validated('attachments', []) as $file) {
            $chapter->addMedia($file)
                ->withCustomProperties(['mime-type' => $file->getMimeType()])
                ->toMediaCollection('attachments', 's3');
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return redirect()->route('stories.chapters.index', ['story' => $chapter->story_id])->with('message', 'Chapter deleted successfully!');
    }
}
