<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Story\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\StoriesRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineResource;
use App\Models\Story;
use App\Models\Timeline;
use Inertia\Inertia;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Story::class, 'story');
    }

    public function index(StoriesRequest $request)
    {
        return Inertia::render('Dashboard/Stories/Index', [
            'stories' => StoryResource::collection(
                $request->stories($request->user()->stories()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
            'timelines' => TimelineResource::collection(
                Timeline::all(['id', 'title'])
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Stories/Create', [
            'timelines' => TimelineResource::collection(
                Timeline::all(['id', 'title'])
            ),
        ]);
    }

    public function write(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Write', [
            'story' => StoryResource::make($story),
        ]);
    }

    public function show(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Show', [
            'story' => StoryResource::make($story),
        ]);
    }

    public function edit(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Edit', [
            'story' => StoryResource::make($story),
        ]);
    }

    public function store(StoreStoryRequest $request)
    {
        $story = Story::create($request->validated() + [
            'status' => Status::PENDING,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('stories.show', compact('story'))->with('message', 'Story created successfully!');
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        //
    }

    public function destroy(Story $story)
    {

    }
}
