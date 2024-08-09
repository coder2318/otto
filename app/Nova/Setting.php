<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Setting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Setting>
     */
    public static $model = \App\Models\Setting::class;

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
        'name', 'enabled',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        if ($request->resourceId == 1) {
            return [
                Text::make('Name', 'name')
                    ->sortable(),

                Select::make('Value', 'value')
                    ->options([
                        'Claude3' => 'Claude3',
                        'GPT4:gpt-4-1106-preview' => 'GPT4:gpt-4-1106-preview',
                        'GPT4:gpt-4o' => 'GPT4:gpt-4o',
                    ])
                    ->sortable(),
            ];
        } elseif ($request->resourceId == 2) {
            return [
                Text::make('Name', 'name')
                    ->sortable(),

                Select::make('Value', 'value')
                    ->options([
                        'whole' => 'Whole',
                        'chunked' => 'Chunked',
                    ])
                    ->sortable(),
            ];
        } elseif ($request->resourceId == 3) {
            return [
                Text::make('Name', 'name')
                    ->sortable(),
                Textarea::make('Value', 'value')
                    ->sortable(),
            ];
        }
        else {
            return [
                Text::make('Name', 'name')
                    ->sortable(),
                Text::make('Value', 'value')
                    ->sortable()->displayUsing(function($value) {
                        return mb_strlen($value) > 120 ? (strip_tags(mb_substr($value, 0, 120)) . " ...") : $value;
                    })->onlyOnIndex(),
            ];
        }
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

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }
}
