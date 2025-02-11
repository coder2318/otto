<?php

namespace App\Nova;

use App\BookCoverParams\BookCoverParams;
use App\Http\Controllers\FontController;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BookCoverTemplate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\BookCoverTemplate>
     */
    public static $model = \App\Models\BookCoverTemplate::class;

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
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:254'),

            Code::make('Base')->language('xml')->height('auto'),

            Image::make('Front image', null, config('media-library.private_disk_name'))->preview(function ($value, $disk) {
                return $value
                    ? Storage::disk($disk)->temporaryUrl($value, now()->addHour())
                    : null;
            })->thumbnail(function ($value, $disk) {
                return $value
                    ? Storage::disk($disk)->temporaryUrl($value, now()->addHour())
                    : null;
            }),

            Code::make('Front')->language('xml')->height('auto'),

            Code::make('Spine')->language('xml')->height('auto'),

            Image::make('Back image', null, config('media-library.private_disk_name'))->preview(function ($value, $disk) {
                return $value
                    ? Storage::disk($disk)->temporaryUrl($value, now()->addHour())
                    : null;
            })->thumbnail(function ($value, $disk) {
                return $value
                    ? Storage::disk($disk)->temporaryUrl($value, now()->addHour())
                    : null;
            }),

            Code::make('Back')->language('xml')->height('auto'),

            BookCoverParams::make('Fields')->withMeta(['fonts' => FontController::getFonts()]),
        ];
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

    public static function group()
    {
        return __('Books');
    }
}
