<?php

namespace App\Nova;

use App\Trait\WithoutReplicationAction;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends Resource
{
    use WithoutReplicationAction;

    /**
     * @var class-string<\App\Models\Customer>
     */
    public static string $model = \App\Models\Customer::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'phone',
        'notes',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()
                ->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->showWhenPeeking()
                ->rules('required', 'max:50')
                ->creationRules('unique:customers,name')
                ->updateRules('unique:customers,name,{{resourceId}}'),

            Text::make(__('Phone'), 'phone')
                ->sortable()
                ->showWhenPeeking()
                ->nullable()
                ->rules( 'max:20'),

            Textarea::make(__('Notes'), 'notes')
                ->showWhenPeeking()
                ->nullable(),

            HasMany::make('Services', 'services', Service::class)
        ];
    }
}
