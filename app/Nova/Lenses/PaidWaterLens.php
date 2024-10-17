<?php

namespace App\Nova\Lenses;

use App\Enums\UserRoles;
use App\Nova\Actions\DeleteWaterNovaAction;
use App\Nova\Apartment;
use App\Nova\Customer;
use App\Nova\Filters\WaterApartmentLevelFilter;
use App\Nova\Filters\WaterApartmentNumberFilter;
use App\Nova\Filters\WaterApartmentTowerFilter;
use App\Nova\Metrics\MonthlyPaidWaterAmount;
use App\Nova\Metrics\TotalPaidWaterAmount;
use App\Nova\User;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class PaidWaterLens extends Lens
{

    public static $search = [];


    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->whereNotNull('paid_at')
        ));
    }


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

            Date::make(__('Start Date'), 'start_date')
                ->filterable()
                ->sortable(),

            Date::make(__('End Date'), 'end_date')
                ->filterable()
                ->sortable(),

            BelongsTo::make('Created By', 'createdBy', User::class)
                ->hideFromIndex()
                ->showOnPreview(),

            URL::make(__('Invoice'), 'receipt_url')
                ->showOnPreview()
                ->exceptOnForms(),

            BelongsTo::make('Paid By', 'paidBy', User::class)
                ->hideFromIndex()
                ->showOnPreview(),

            DateTime::make(__('Paid At'), 'paid_at')
                ->filterable()
                ->sortable(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new TotalPaidWaterAmount(),
            new MonthlyPaidWaterAmount(),
            (new NovaDetachedFilters([
                (new WaterApartmentTowerFilter())->withMeta(['width' => 'w-1/3']),
                (new WaterApartmentLevelFilter())->withMeta(['width' => 'w-1/3']),
                (new WaterApartmentNumberFilter())->withMeta(['width' => 'w-1/3']),
            ]))->width('full'),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new WaterApartmentTowerFilter(),
            new WaterApartmentLevelFilter(),
            new WaterApartmentNumberFilter(),
        ];
    }


    public function actions(NovaRequest $request): array
    {
        return [
            (new DeleteWaterNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->onlyInline(),
        ];
    }

    public function uriKey(): string
    {
        return 'paid-water-lens';
    }

    public function name(): string
    {
        return 'Paid Water';
    }
}
