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
use App\Services\DeepgramService;
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

    public function type(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Type', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'transcription' => fn () => session('transcription'),
        ]);
    }

    public function recordings(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Recordings', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('recordings')
            ),
        ]);
    }

    public function transcribe(Chapter $chapter, TranscribeRequest $request, DeepgramService $deepgram)
    {
        $this->authorize('update', $chapter);

        /** @var \Illuminate\Database\Eloquent\Collection<\Spatie\MediaLibrary\MediaCollections\Models\Media> */
        $media = $chapter->recordings()->whereIn('id', $request->validated('recordings'))->get();

        foreach ($media as $record) {
            $transcription[] = $deepgram->transcribeMedia($record);
        }

        return redirect()->route('chapters.type', compact('chapter'))->with('transcription', $transcription);
    }

    public function deleteRecording(Chapter $chapter, int $recording)
    {
        abort_unless($recording = $chapter->recordings()->find($recording), 404);

        $this->authorize('update', $chapter);

        $recording->delete();

        return redirect()->route('chapters.recordings', compact('chapter'))->with('message', 'Recording deleted successfully!');
    }

    public function record(Chapter $chapter)
    {
        $this->authorize('update', $chapter);

        return Inertia::render('Dashboard/Chapters/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
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
        /** @var UploadedFile */
        foreach ($request->validated('recordings', []) as $record) {
            $chapter->addMedia($record)->toMediaCollection('recordings');
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
        foreach ($request->validated('recordings', []) as $record) {
            $chapter->addMedia($record)->withCustomProperties(['mime-type' => 'audio/webm'])->toMediaCollection('recordings', 's3');
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
