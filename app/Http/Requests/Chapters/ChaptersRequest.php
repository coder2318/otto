<?php

namespace App\Http\Requests\Chapters;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\TimelineQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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

        return $this->crutchCovers($chapters->union($questions)
            ->with(['question', 'cover'])
            ->orderBy('created_at', 'asc')
            ->paginate(isset($this->filter['timeline_id']) ? 5 : 6)
            ->appends($this->query())
        );
    }

    protected function crutchCovers(AbstractPaginator $results)
    {
        $questions = $results->where('type', 'question')->pluck('id')->merge(
            $results->where('type', 'chapter')->pluck('timeline_question_id')
        )->unique();

        $covers = Media::query()
            ->where('model_type', TimelineQuestion::class)
            ->whereIn('model_id', $questions)
            ->get()
            ->keyBy('model_id');

        $results->getCollection()->transform(function (Chapter $questionChapter) use ($covers) {
            if ($questionChapter->type === 'question') { // @phpstan-ignore-line
                $questionChapter->setRelation('cover', $covers->get($questionChapter->id));
            }

            if ($questionChapter->type === 'chapter') {
                $questionChapter->setRelation('cover', $covers->get($questionChapter->timeline_question_id));
            }

            return $questionChapter;
        });

        return $results;
    }
}
