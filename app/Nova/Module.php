<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Lenses\Paths;
use App\Nova\Lenses\Courses;
use App\Nova\Lenses\Chapters;
use App\Nova\Lenses\Lessons;
use App\Nova\Lenses\Tests;


class Module extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Module>
     */
    public static $model = \App\Models\Module::class;
    public static $group = 'Learning Content';
    public static $priority = 1;

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
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name')->sortable(),

            Select::make('Category')
                    ->options(array_combine(\App\Models\Module::LEARNING_CATEGORIES, \App\Models\Module::LEARNING_CATEGORIES))
                    ->sortable()
                    ->rules('required', 'in:' . implode(',', \App\Models\Module::LEARNING_CATEGORIES)),
        
            Text::make('Description')->sortable()->hideFromIndex(),

            BelongsTo::make('Tenant')->hideFromIndex(),

            BelongsToMany::make('Parent Modules', 'parents', Module::class)
                ->fields(function () {
                    return [
                        Number::make('Sort Order', 'sort_order')->sortable(),
                        Boolean::make('Is Active', 'is_active_relation')
                    ];
                }),

            BelongsToMany::make('Child Modules', 'children', Module::class)
                ->fields(function () {
                    return [
                        Number::make('Sort Order', 'sort_order')->sortable(),
                        Boolean::make('Is Active', 'is_active_relation')
                    ];
                }),

            BelongsToMany::make('Content Types', 'contentTypes', ContentType::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            new Paths,
            new Courses,
            new Chapters,
            new Lessons,
            new Tests,
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
