<?php

namespace App\Nova;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use LimeDeck\NovaCashierOverview\Subscription;
use Vyuldashev\NovaPermission\Permission;
use Vyuldashev\NovaPermission\Role;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'email',
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

            Avatar::make('Avatar', 'avatar')
                ->store(function (Request $request, UserModel $user) {
                    $user->clearMediaCollection('avatar');
                    $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');

                    return true;
                })
                ->preview(function () {
                    return $this->getFirstMediaUrl('avatar');
                })
                ->thumbnail(function () {
                    return $this->getFirstMediaUrl('avatar');
                })
                ->delete(function () {
                    $this->clearMediaCollection('avatar');
                })
                ->deletable()
                ->maxWidth(50),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            Subscription::make(),

            MorphToMany::make('Roles', 'roles', Role::class),

            MorphToMany::make('Permissions', 'permissions', Permission::class),
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
        return __('nova-permission-tool::navigation.sidebar-label');
    }
}
