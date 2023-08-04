<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\ChaptersRequest;
use App\Http\Requests\Chapters\StoreChapterRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineResource;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\Timeline;
use Inertia\Inertia;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Chapter::class, 'story');
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
        return Inertia::render('Dashboard/Chapters/Type', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function record(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Record', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Story $story)
    {
        return Inertia::render('Dashboard/Chapters/Create', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Story $story, StoreChapterRequest $request)
    {
        $chapter = $story->chapters()->create($request->validated() + [
            'status' => Status::DRAFT,
        ]);

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
