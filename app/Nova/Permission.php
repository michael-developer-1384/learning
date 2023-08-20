<?php

namespace App\Nova;

use App\Models\Permission as PermissionModel;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsToMany;

use Laravel\Nova\Http\Requests\NovaRequest;

class Permission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Permission>
     */
    public static $model = \App\Models\Permission::class;
    public static $group = 'Operational Data';
    public static $priority = 7;


    const PERMISSIONS = [
        ['name' => 'view_courses', 'description' => 'View all courses', 'category' => 'course'],
        ['name' => 'create_courses', 'description' => 'Create new courses', 'category' => 'course'],
        ['name' => 'edit_courses', 'description' => 'Edit existing courses', 'category' => 'course'],
        ['name' => 'delete_courses', 'description' => 'Delete courses', 'category' => 'course'],

        ['name' => 'view_users', 'description' => 'View all users', 'category' => 'user'],
        ['name' => 'create_users', 'description' => 'Create new users', 'category' => 'user'],
        ['name' => 'edit_users', 'description' => 'Edit existing users', 'category' => 'user'],
        ['name' => 'delete_users', 'description' => 'Delete users', 'category' => 'user'],

        ['name' => 'view_reports', 'description' => 'View all reports', 'category' => 'report'],
        ['name' => 'generate_reports', 'description' => 'Generate new reports', 'category' => 'report'],

        ['name' => 'view_billing', 'description' => 'View billing details', 'category' => 'billing'],
        ['name' => 'edit_billing', 'description' => 'Edit billing details', 'category' => 'billing'],

        ['name' => 'view_settings', 'description' => 'View system settings', 'category' => 'settings'],
        ['name' => 'edit_settings', 'description' => 'Edit system settings', 'category' => 'settings'],

        ['name' => 'view_content', 'description' => 'View all content', 'category' => 'content'],
        ['name' => 'create_content', 'description' => 'Create new content', 'category' => 'content'],
        ['name' => 'edit_content', 'description' => 'Edit existing content', 'category' => 'content'],
        ['name' => 'delete_content', 'description' => 'Delete content', 'category' => 'content'],

        ['name' => 'view_analytics', 'description' => 'View analytics data', 'category' => 'analytics'],
        ['name' => 'generate_analytics', 'description' => 'Generate analytics reports', 'category' => 'analytics'],

        ['name' => 'send_emails', 'description' => 'Send emails to users', 'category' => 'communication'],
        ['name' => 'view_communications', 'description' => 'View all communications', 'category' => 'communication'],
    ];

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
        'id', 'name',
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

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Description')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Category')
                ->options(array_combine(PermissionModel::CATEGORIES, PermissionModel::CATEGORIES))
                ->sortable()
                ->rules('required'),
            
                BelongsToMany::make('Roles')
                        ->fields(function () {
                            return [
                                Boolean::make('Is Active'),
                            ];
                        }),
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
