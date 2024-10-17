<?php

namespace App\Nova;

use App\Enums\UserRoles;
use App\Nova\Actions\DeleteBulkWaterNovaAction;
use App\Nova\Actions\DeleteWaterNovaAction;
use App\Nova\Actions\PayWaterNovaAction;
use App\Nova\Actions\WaterCreateAction;
use App\Nova\Filters\WaterApartmentLevelFilter;
use App\Nova\Filters\WaterApartmentNumberFilter;
use App\Nova\Filters\WaterApartmentTowerFilter;
use App\Nova\Filters\WaterIsPaidFilter;
use App\Nova\Lenses\PaidWaterLens;
use App\Nova\Metrics\MonthlyUnPaidWaterAmount;
use App\Nova\Metrics\TotalUnPaidWaterAmount;
use App\Trait\WithoutReplicationAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class Water extends Resource
{
    use WithoutReplicationAction;

    public static string $model = \App\Models\Water::class;

    public static $search = [
        'id', 'customer.name', 'rentCustomer.name'
    ];

    public static $with = ['customer', 'apartment', 'rentCustomer', 'createdBy', 'paidBy'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()
                ->sortable(),

            BelongsTo::make('Apartment', 'apartment', Apartment::class)
                ->filterable()
                ->searchable()
                ->sortable(),

            BelongsTo::make('Customer', 'customer', Customer::class)
                ->filterable()
                ->searchable()
                ->sortable(),

            Boolean::make('Is Rent', 'is_rent')
                ->filterable()
                ->sortable(),

            BelongsTo::make('Rent Customer', 'rentCustomer', Customer::class)
                ->filterable()
                ->searchable()
                ->sortable(),

            Currency::make(__('Amount'), 'amount')
                ->currency('IQD')
                ->filterable()
                ->sortable(),

            Boolean::make(__('Is Paid'), 'is_paid'),

            Date::make(__('Start Date'), 'start_date')
                ->filterable()
                ->sortable(),

            Date::make(__('End Date'), 'end_date')
                ->filterable()
                ->sortable(),

            BelongsTo::make('Created By', 'createdBy', User::class)
                ->hideFromIndex()
                ->showOnPreview(),

            BelongsTo::make('Paid By', 'paidBy', User::class)
                ->hideFromIndex()
                ->showOnPreview(),

            DateTime::make(__('Paid At'), 'paid_at')
                ->hideFromIndex(),

            URL::make(__('Invoice'), 'receipt_url')
                ->showOnPreview()
                ->exceptOnForms(),

            Textarea::make(__('Notes'), 'notes')
                ->showOnPreview()
                ->sortable(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new WaterIsPaidFilter(),
            new WaterApartmentTowerFilter(),
            new WaterApartmentLevelFilter(),
            new WaterApartmentNumberFilter(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new WaterCreateAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->extraClasses('bg-primary-500 text-white hover:black')
                ->icon('plus'),

            (new DeleteBulkWaterNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->extraClasses('bg-red-500 text-white hover:black')
                ->icon('trash'),

            (new PayWaterNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN || $request->user()->role === UserRoles::NORMAL_USER)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->onlyInline(),

            (new DeleteWaterNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->onlyInline(),
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [
            PaidWaterLens::make(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new TotalUnPaidWaterAmount(),
            new MonthlyUnPaidWaterAmount(),
            (new NovaDetachedFilters([
                (new WaterApartmentTowerFilter())->withMeta(['width' => 'w-1/3']),
                (new WaterApartmentLevelFilter())->withMeta(['width' => 'w-1/3']),
                (new WaterApartmentNumberFilter())->withMeta(['width' => 'w-1/3']),
            ]))->width('full'),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        if($request->viaRelationship()) {
            return $query;
        }
        return $query->whereNull('paid_at');
    }

    public static function authorizedToCreate(Request $request): false
    {
        return false;
    }

    public function authorizedToDelete(Request $request): false
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): false
    {
        return false;
    }
}
