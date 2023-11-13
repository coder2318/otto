<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'search' => ['string', 'nullable'],
        ]);

        if (! $request->search) {
            return redirect()->back()->with('search', []);
        }

        return redirect()->back()->with('search', [
            'stories' => StoryResource::collection(
                $request->user()->stories()
                    ->whereIn('id', $order = Story::search($request->search)->keys())
                    ->get(['id', 'title'])
                    ->sortBy(fn ($model) => array_search($model->getKey(), $order->all()))
            ),
            'chapters' => ChapterResource::collection(
                $request->user()->chapters()
                    ->whereIn('chapters.id', $order = Chapter::search($request->search)->keys())
                    ->get(['chapters.id', 'chapters.content', 'chapters.title'])
                    ->sortBy(fn ($model) => array_search($model->getKey(), $order->all()))
            ),
        ]);
    }
}
