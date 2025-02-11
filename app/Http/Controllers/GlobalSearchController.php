<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineQuestionResource;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\TimelineQuestion;
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
            'questions' => TimelineQuestionResource::collection(
                TimelineQuestion::query()
                    ->whereNotIn('id', $request->user()->chapters()
                        ->distinct()->whereNotNull('timeline_question_id')
                        ->pluck('timeline_question_id')
                    )
                    ->whereIn('id', $order = TimelineQuestion::search($request->search)->keys())
                    ->get(['id', 'question', 'context'])
                    ->sortBy(fn ($model) => array_search($model->getKey(), $order->all()))
            ),
        ]);
    }
}
