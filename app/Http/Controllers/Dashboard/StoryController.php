<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Story\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stories\ChapterOrderRequest;
use App\Http\Requests\Stories\StoreStoryRequest;
use App\Http\Requests\Stories\StoriesRequest;
use App\Http\Requests\Stories\UpdateStoryRequest;
use App\Http\Resources\BookCoverTemplateResource;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineResource;
use App\Models\BookCoverTemplate;
use App\Models\Story;
use App\Models\Timeline;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'stories' => fn () => StoryResource::collection(
                $request->stories($request->user()->stories()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Stories/Create', [
            'timelines' => fn () => TimelineResource::collection(
                Timeline::all(['id', 'title'])
            ),
        ]);
    }

    public function write(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Write', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function show(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Show', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function edit(Story $story, Request $request)
    {
        return Inertia::render('Dashboard/Stories/Edit', [
            'story' => fn () => StoryResource::make($story),
            'template' => fn () => BookCoverTemplateResource::make(
                BookCoverTemplate::when(
                    $tmpl = $request->query('template'),
                    fn ($query) => $query->where('id', $tmpl)
                )->firstOrFail()
            ),
        ]);
    }

    public function store(StoreStoryRequest $request)
    {
        /** @var Story $story */
        $story = Story::create($request->validated() + [
            'status' => Status::PENDING,
            'user_id' => $request->user()->id,
        ]);

        if ($request->hasFile('cover')) {
            $story->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return redirect()->route('stories.show', compact('story'))->with('message', 'Story created successfully!');
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $story->update($request->validated());

        if ($request->hasFile('cover')) {
            $story->cover?->delete();
            $story->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return $this->redirectBackOrRoute($request)->with('message', 'Story updated successfully!');
    }

    public function destroy(Story $story)
    {
        DB::transaction(function () use ($story) {
            $story->cover?->delete();
            $story->delete();
        });

        return redirect()->route('stories.index')->with('message', 'Story deleted successfully!');
    }

    public function covers(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Covers', [
            'story' => fn () => StoryResource::make($story),
            'covers' => fn () => BookCoverTemplateResource::collection(
                BookCoverTemplate::paginate(12)
            ),
        ]);
    }

    public function contents(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Contents', [
            'story' => fn () => StoryResource::make($story),
            'chapters' => fn () => $story->chapters()
                ->orderBy('order', 'asc')
                ->get([
                    'id', 'title', 'status', 'timeline_id', 'order',
                ])
                ->groupBy('timeline_id')
                ->map(fn ($chapters) => ChapterResource::collection($chapters)),
            'timelines' => fn () => TimelineResource::collection(
                Timeline::all(['id', 'title'])
            ),
        ]);
    }

    public function saveContents(Story $story, ChapterOrderRequest $request)
    {
        DB::transaction(function () use ($story, $request) {
            foreach ($request->validated('timelines', []) as $data) {
                if (empty($data['chapters'])) {
                    continue;
                }

                $story->chapters()->whereIntegerInRaw('id', $data['chapters'])->update([
                    'timeline_id' => $data['id'],
                ]);

                foreach ($data['chapters'] as $order => $chapter) {
                    $story->chapters()->where('id', $chapter)->update(compact('order'));
                }
            }
        });

        return $this->redirectBackOrRoute($request)->with('message', 'Contents saved successfully!');
    }

    public function preview(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Preview', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function book(Story $story)
    {
        $chapters = $story->chapters()->orderBy('timeline_id', 'asc')->orderBy('order', 'asc')->lazy();
        return Pdf::loadView('pdf.book', compact('story', 'chapters'))->stream('demo.pdf');
    }
}
