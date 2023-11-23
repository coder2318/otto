<?php

namespace App\Http\Requests\Chapters;

use App\Models\Chapter;
use App\Models\Media;
use App\Models\Story;
use App\Models\TimelineQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
    protected function builder($builder): QueryBuilder|Builder
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
        $chapters = $this->builder($story->chapters()
            ->leftJoin('timeline_questions as tq', 'tq.id', '=', 'chapters.timeline_question_id')
            ->select([
                'chapters.id',
                'chapters.timeline_id',
                'chapters.title',
                DB::raw('tq.context as context'),
                'chapters.status',
                'chapters.timeline_question_id',
                'chapters.guest_id',
                DB::raw("'chapter' as type"),
                'chapters.created_at',
            ])
        );

        // Injecting unanswered questions
        $questions = $this->builder($story->storyType->questions()
            ->select([
                'timeline_questions.id',
                'timeline_questions.timeline_id',
                DB::raw('question as title'),
                'context',
                DB::raw("'undone' as status"),
                DB::raw('null as timeline_question_id'),
                DB::raw('null as guest_id'),
                DB::raw("'question' as type"),
                'timeline_questions.created_at',
            ])
            ->whereNotIn('timeline_questions.id', $story
                ->chapters()
                ->whereNotNull('timeline_question_id')
                ->distinct()
                ->pluck('timeline_question_id')
            ));

        $query = $chapters->union($questions);

        $query = Chapter::from(DB::raw("({$query->toSql()}) as a")) // @phpstan-ignore-line
            ->select('a.*')
            ->addBinding($query->getBindings());

        return $this->loadRelations($query
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(6)
            ->appends($this->query())
        );
    }

    protected function loadRelations($results)
    {
        $questions = $results->where('type', 'question')->pluck('id')->merge(
            $questionsRelations = $results->where('type', 'chapter')->pluck('timeline_question_id')->filter()
        )->unique()->values();

        $chapters = $results->where('type', 'chapter')->pluck('id')->unique()->values();

        $covers = Media::query()
            ->where('collection_name', 'cover')
            ->where(fn ($q) => $q
                ->where('model_type', TimelineQuestion::class)
                ->whereIn('model_id', $questions)
            )
            ->orWhere(fn ($q) => $q
                ->where('model_type', Chapter::class)
                ->whereIn('model_id', $chapters)
            )
            ->get()
            ->groupBy('model_type')
            ->map(fn ($q) => $q->keyBy('model_id'));

        $questionsRelations = TimelineQuestion::query()
            ->whereIn('id', $questionsRelations)
            ->get()
            ->keyBy('id');

        $results->getCollection()->transform(function ($questionChapter) use ($covers, $questionsRelations) {
            if ($questionChapter->type === 'question') {
                $questionChapter->cover = $covers->get(TimelineQuestion::class)?->get($questionChapter->id);
                $questionChapter->question = null;
            }

            if ($questionChapter->type === 'chapter') {
                $questionChapter->cover = $covers->get(Chapter::class)?->get($questionChapter->id) ??
                    $covers->get(TimelineQuestion::class)?->get($questionChapter->timeline_question_id);

                $questionChapter->question = $questionsRelations->get($questionChapter->timeline_question_id) ?? null;
            }

            return $questionChapter;
        });

        return $results;
    }
}
