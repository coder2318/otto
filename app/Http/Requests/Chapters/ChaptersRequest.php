<?php

namespace App\Http\Requests\Chapters;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\TimelineQuestion;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * TODO: Rewrite this whole shit if lags out.
 */
class ChaptersRequest extends FormRequest
{
    protected function builder($builder): QueryBuilder
    {
        return QueryBuilder::for($builder, $this)
            ->allowedFilters([
                AllowedFilter::exact('timeline_id'),
                AllowedFilter::callback('status', function (EloquentBuilder $query, $value) {
                    if ($query->getModel() instanceof Chapter) {
                        $query->where('status', $value);
                    }

                    // Crutch to remove questions from draft/published statuses
                    if ($query->getModel() instanceof TimelineQuestion) {
                        match ($value) {
                            'draft', 'published' => $query->whereNull('timeline_questions.id'),
                            default => $query,
                        };
                    }
                }),
            ]);
    }

    public function chaptersQuestions(Story &$story)
    {
        return $this->builder($story->chapters()
            ->join('timeline_questions as tq', 'tq.id', '=', 'chapters.timeline_question_id')
            ->select([
                'chapters.id',
                'chapters.timeline_id',
                'title',
                DB::raw('tq.context as context'),
                'status',
                'timeline_question_id',
                DB::raw("'chapter' as type"),
                'chapters.created_at',
            ])->union(
                $this->builder($story->storyType->questions() // @phpstan-ignore-line
                    ->select([
                        'timeline_questions.id',
                        'timeline_questions.timeline_id',
                        DB::raw('question as title'),
                        'context',
                        DB::raw("'undone' as status"),
                        DB::raw('null as timeline_question_id'),
                        DB::raw("'question' as type"),
                        'timeline_questions.created_at',
                    ])
                    ->whereNotIn('timeline_questions.id', $story
                        ->chapters()
                        ->whereNotNull('timeline_question_id')
                        ->distinct()
                        ->pluck('timeline_question_id')
                    )
                ))->with(['cover', 'question'])->orderBy('created_at', 'desc')
        );
    }
}
