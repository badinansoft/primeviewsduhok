<?php

namespace App\Nova;

use App\Trait\WithoutReplicationAction;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tower extends Resource
{
    use WithoutReplicationAction;

    /**
     * @var class-string<\App\Models\Tower>
     */
    public static string $model = \App\Models\Tower::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()
                ->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:towers,name')
                ->updateRules('unique:towers,name,{{resourceId}}'),
        ];
    }
}
