<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;

use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Actions\ProcessUserImport;


class FileImport extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FileImport>
     */
    public static $model = \App\Models\FileImport::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'original_filename';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'original_filename'
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
            Text::make('Original Filename')->sortable()->exceptOnForms(),
            BelongsTo::make('Tenant'),
            BelongsTo::make('Created By', 'createdBy', User::class),
            Select::make('Content')
                    ->options(array_combine(array_keys(\App\Models\FileImport::IMPORT_CONTENT), array_keys(\App\Models\FileImport::IMPORT_CONTENT)))
                    ->rules('required'),  
            Select::make('Type')
                    ->options(array_combine(array_keys(\App\Models\FileImport::IMPORT_TYPES), array_keys(\App\Models\FileImport::IMPORT_TYPES)))
                    ->rules('required'),
            File::make('File', 'filename') // Der zweite Parameter ist der Spaltenname in der Datenbank
                    ->disk('local') // Der Disk-Name, auf dem die Datei gespeichert werden soll. Sie können dies an Ihre Bedürfnisse anpassen.
                    ->storeOriginalName('original_filename') // Speichert den Originalnamen der Datei in der angegebenen Spalte
                    ->prunable() // Löscht die Datei vom Disk, wenn das Modell gelöscht wird
                    ->rules('mimes:xls,xlsx,csv'),
            HasMany::make('File Import Users'),
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
        return [
            new ProcessUserImport,
        ];
    }

    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['FileImportUsers' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);
    }

}
