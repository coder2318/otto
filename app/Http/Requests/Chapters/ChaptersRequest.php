<?php

namespace App\Http\Requests\Chapters;

use App\Models\Chapter;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChaptersRequest extends FormRequest
{
    public function chapters(Builder|string $builder = Chapter::class): QueryBuilder
    {
        return QueryBuilder::for($builder)
            ->allowedFilters([
                AllowedFilter::exact('timeline_id'),
                AllowedFilter::exact('status'),
            ]);
    }
}
