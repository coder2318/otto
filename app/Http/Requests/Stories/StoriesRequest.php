<?php

namespace App\Http\Requests\Stories;

use App\Models\Story;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StoriesRequest extends FormRequest
{
    public function stories(Builder|string $builder = Story::class): QueryBuilder
    {
        return QueryBuilder::for($builder)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('user_id'),
            ]);
    }
}
