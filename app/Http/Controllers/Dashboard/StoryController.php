<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Dashboard/Stories/Index', [
            'stories' => StoryResource::collection(
                $request->user()->stories()->with('cover')->paginate(4)
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
