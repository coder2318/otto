<?php

namespace App\Http\Requests\Chapters;

use App\Models\Story;
use App\Models\TimelineQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChaptersRequest extends FormRequest
{
    protected function builder($builder): QueryBuilder|Builder
    {
        return QueryBuilder::for($builder, $this)
            ->allowedFilters([
                AllowedFilter::exact('timeline_id'),
                AllowedFilter::callback('status', fn ($q, $val) => $q->where('a.status', $val)),
            ]);
    }

    public function questions(Story &$story)
    {
        $questions = $story->storyType->questions()
            ->leftJoin('chapters as c', fn ($q) => $q
                ->on('c.timeline_question_id', '=', 'timeline_questions.id')
                ->where('c.story_id', '=', $story->id)
            )
            ->select([
                'timeline_questions.id',
                'c.id as chapter_id',
                'timeline_questions.question',
                'timeline_questions.context',
                DB::raw('COALESCE(`c`.`status`, "undone") as `status`'),
                DB::raw('COALESCE(`c`.`created_at`, `timeline_questions`.`created_at`) as `created_at`'),
            ])->getQuery();

        $chapters = $story->chapters()->whereNull('timeline_question_id')
            ->select([
                DB::raw('NULL as `id`'),
                'id as chapter_id',
                'title as question',
                DB::raw('NULL as `context`'),
                'status',
                'created_at',
            ])->getQuery();

        $query = $questions->unionAll($chapters)->getQuery()->orderBy('created_at', 'desc');
        $query = TimelineQuestion::from(DB::raw("({$query->toSql()}) as a"))
            ->setBindings($query->getRawBindings())
            ->with(['chapter.cover', 'cover']);

        return $this->builder($query);
    }
}
