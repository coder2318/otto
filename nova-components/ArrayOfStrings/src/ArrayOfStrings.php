<?php

namespace App\ArrayOfStrings;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ArrayOfStrings extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'array-of-strings';

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }
}
