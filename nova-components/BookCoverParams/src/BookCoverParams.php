<?php

namespace App\BookCoverParams;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class BookCoverParams extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'book-cover-params';

    public $showOnIndex = false;

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }
}
