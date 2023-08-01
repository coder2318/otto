<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\StoriesRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Inertia\Inertia;

class StoryController extends Controller
{
    public function index(StoriesRequest $request)
    {
        return Inertia::render('Dashboard/Stories/Index', [
            'query' => $request->query(),
            'stories' => StoryResource::collection(
                $request->stories($request->user()->stories()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Stories/Create');
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
        //
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        //
    }

    public function destroy(Story $story)
    {
        //
    }
}
