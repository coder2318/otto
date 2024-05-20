<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class LuluPrintSettings extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Setting>
     */
    public static $model = \App\Models\LuluPrintSettings::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $fields = [ 
            Text::make('Name', 'name')->sortable(),
            Boolean::make('Is Enabled', 'is_enabled')->sortable()
        ];

        $luluOptions = \App\Models\LuluPrintSettings::getAvailableOptions();

        foreach ($luluOptions as $fieldName => $valuesByKeys) {
            $fieldCaption = ucwords(str_replace('_', ' ', $fieldName));
            $options = array_keys($valuesByKeys);
            $options = array_combine($options, $options);
            $fields[] = Select::make($fieldCaption, $fieldName)->options($options);
        }

        return $fields;
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public static function group()
    {
        return __('Books');
    }

}
