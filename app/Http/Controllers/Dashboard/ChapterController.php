<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\ChaptersRequest;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
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
        $this->authorizeResource(Story::class, 'story');
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
    public function store(StoreChapterRequest $request)
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
    public function update(UpdateChapterRequest $request, Chapter $chapter)
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
