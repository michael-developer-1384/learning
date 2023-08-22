<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class FilterCriterion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FilterCriterion>
     */
    public static $model = \App\Models\FilterCriterion::class;

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
            ID::make()->sortable(),
            BelongsTo::make('Filter'),

            Select::make('Chain Operator')
                ->options([
                    'User' => 'User',
                    'Company' => 'Company',
                    'Department' => 'Department',
                    'LearningType' => 'Learning Type',
                    'Position' => 'Position',
                    'Role' => 'Role',
                ])
                ->required()
                ->help('The model to be filtered (e.g., User, Company, etc.).'),

            Select::make('Operator')
                ->options([
                    '=' => '=',
                    '!=' => '!=',
                    '<' => '<',
                    '>' => '>',
                    '<=' => '<=',
                    '>=' => '>=',
                    'LIKE' => 'LIKE',
                    'NOT LIKE' => 'NOT LIKE',
                    'IN' => 'IN',
                    'NOT IN' => 'NOT IN',
                ])
                ->rules('required')
                ->help('The operator used for filtering.'),

            Text::make('Value')
                ->rules('required')
                ->help('The value against which filtering is done.'),

            Select::make('Chain Operator')
                ->options([
                    'AND' => 'AND',
                    'OR' => 'OR',
                ])
                ->nullable()
                ->help('The chaining operator that determines how this criterion is linked with the next one.'),

            Number::make('Sort Order')
                ->default(0)
                ->help('A numeric value determining the order of the criteria.'),

            Boolean::make('Group Start')
                ->help('Start of a bracket group.'),

            Boolean::make('Group End')
                ->help('End of a bracket group.'),

            BelongsTo::make('Tenant')->onlyOnForms(),
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
        return [];
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
