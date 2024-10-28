<?php

namespace App\Nova;

use App\Enums\ApartmentView;
use App\Enums\UserRoles;
use App\Nova\Filters\ApartmentLevelFilter;
use App\Nova\Filters\ApartmentNumberFilter;
use App\Nova\Filters\ApartmentTowerFilter;
use App\Trait\WithoutReplicationAction;
use Datomatic\Nova\Fields\Enum\Enum;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class Apartment extends Resource
{
    use WithoutReplicationAction;

    /**
     * @var class-string<\App\Models\Apartment>
     */
    public static string $model = \App\Models\Apartment::class;

    /**
     * @var string
     */
    public static $title = 'title';

    /**
     * @var array
     */
    public static $search = [
        'id',
        'number',
        'view',
        'is_rent',
        'customer.name',
    ];

    public static $with = ['level', 'customer', 'tower'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()
                ->sortable(),

            Text::make(__('Title'), 'title')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('Number'), 'number')
                ->sortable()
                ->rules('required', 'integer', 'min:1', 'max:100'),

            Enum::make(__('View'), 'view')
                ->attach(ApartmentView::class)
                ->sortable()
                ->filterable()
                ->rules('required'),

            Currency::make(__('Balance'), 'balance')
                ->sortable()
                ->showWhenPeeking()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Currency::make(__('Balance USD'), 'balance_usd')
                ->currency('USD')
                ->context(new \Brick\Money\Context\CustomContext(0))
                ->sortable()
                ->showWhenPeeking()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Number::make(__('Last Unit'), 'gas_unit')
                ->sortable()
                ->showWhenPeeking()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Number::make(__('Area'), 'area')
                ->sortable()
                ->showWhenPeeking(),

            BelongsTo::make(__('Tower'), 'tower', Tower::class)
                ->filterable()
                ->searchable()
                ->hideFromIndex()
                ->showCreateRelationButton()
                ->rules('required'),

            BelongsTo::make(__('Level'), 'level', Level::class)
                ->searchable()
                ->hideFromIndex()
                ->showCreateRelationButton()
                ->filterable()
                ->rules('required'),

            BelongsTo::make(__('Customer'), 'customer', Customer::class)
                ->searchable()
                ->showCreateRelationButton()
                ->filterable()
                ->nullable(),

            Boolean::make(__('Is Rent'), 'is_rent')
                ->sortable()
                ->filterable()
                ->rules('required'),

            BelongsTo::make(__('Rent Customer'), 'rentCustomer', Customer::class)
                ->searchable()
                ->showCreateRelationButton()
                ->filterable()
                ->nullable(),

            Boolean::make(__('Status'), 'status')
                ->sortable()
                ->filterable()
                ->rules('required'),

            URL::make(__('Profile'), 'profile')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            HasMany::make(__('Services'), 'services', Service::class),
            HasMany::make(__('Water'), 'waters', Water::class),
            HasMany::make(__('Gas'), 'gas', Gas::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new Metrics\ApartmentCount,
            new Metrics\ApartmentViewPartition,
            new Metrics\ApartmentRentPartition,
            (new NovaDetachedFilters([
                (new ApartmentTowerFilter())->withMeta(['width' => 'w-1/3']),
                (new ApartmentLevelFilter())->withMeta(['width' => 'w-1/3']),
                (new ApartmentNumberFilter())->withMeta(['width' => 'w-1/3']),
            ]))->width('full')->withReset(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new Actions\CreateApartmentSingleServiceNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->showAsButton()
                ->onlyInline(),

            (new Actions\CreateGasForApartmentNovaAction($this->resource))
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN || $request->user()->role === UserRoles::NORMAL_USER)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->showAsButton(true)
                ->onlyInline(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new ApartmentTowerFilter(),
            new ApartmentLevelFilter(),
            new ApartmentNumberFilter(),
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [
            (new Lenses\TotalCollectByDate())->canSee(fn() => $request->user()->role !== UserRoles::NORMAL_USER),
        ];
    }

    public function serializeForIndex(NovaRequest $request, $fields = null): array
    {
        $serialized = parent::serializeForIndex($request, $fields);

        if ($request->lens) {
            // If a lens is being viewed
            $serialized = array_merge($serialized, [
                'authorizedToView' => false,
                'authorizedToUpdate' => false,
                'authorizedToDelete' => false,
                'authorizedToRestore' => false,
                'authorizedToForceDelete' => false,
                'authorizedToReplicate' => false,
            ]);
        }

        return $serialized;
    }
}
