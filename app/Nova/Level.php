<?php

namespace App\Nova;

use App\Trait\WithoutReplicationAction;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Level extends Resource
{
    use WithoutReplicationAction;

    /**
     * @var class-string<\App\Models\Level>
     */
    public static string $model = \App\Models\Level::class;

    public static $title = 'title';

    /**
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:50')
                ->creationRules('unique:levels,name')
                ->updateRules('unique:levels,name,{{resourceId}}'),
        ];
    }

}
