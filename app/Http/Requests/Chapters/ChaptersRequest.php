<?php

namespace App\Http\Requests\Chapters;

use App\Models\Story;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChaptersRequest extends FormRequest
{
    protected function builder($builder): QueryBuilder|Builder
    {
        return QueryBuilder::for($builder, $this)
            ->allowedFilters([
                AllowedFilter::exact('timeline_id'),
                AllowedFilter::callback('status', fn (EloquentBuilder $q, $value) => $q->whereRaw("COALESCE(chapters.status, 'undone') = '$value'")
                ),
            ]);
    }

    public function questions(Story &$story)
    {
        $questions = $story->storyType->questions()
            ->leftJoin('chapters', fn ($q) => $q
                ->on('timeline_questions.id', '=', 'chapters.timeline_question_id')
                ->where('chapters.story_id', '=', $story->id)
            )
            ->with([
                'chapter' => fn ($q) => $q->with('cover')->where('story_id', $story->id),
                'cover',
            ])
            ->orderBy('chapters.created_at', 'desc')
            ->orderBy('timeline_questions.created_at', 'desc');

        return $this->builder($questions);
    }
}
