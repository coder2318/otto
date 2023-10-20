<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Story $book)
    {
        return Inertia::render('Dashboard/Books/Show', [
            'story' => fn () => StoryResource::make($book->load([
                'chapters' => fn ($q) => $q->orderBy('timeline_id', 'asc')
                    ->orderBy('order', 'asc')
                    ->where('status', Status::PUBLISHED)->limit(1),
            ])),
        ]);
    }
}
