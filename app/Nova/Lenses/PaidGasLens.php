<?php

namespace App\Nova\Lenses;

use App\Nova\Apartment;
use App\Nova\Customer;
use App\Nova\Filters\ServiceApartmentLevelFilter;
use App\Nova\Filters\ServiceApartmentNumberFilter;
use App\Nova\Filters\ServiceApartmentTowerFilter;
use App\Nova\Metrics\MonthlyPaidGasAmount;
use App\Nova\Metrics\TotalPaidGasAmount;
use App\Nova\User;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class PaidGasLens extends Lens
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
            ID::make()->sortable(),

            BelongsTo::make(__('Apartment'), 'apartment', Apartment::class)
                ->searchable()
                ->sortable(),

            BelongsTo::make(__('Customer'), 'customer', Customer::class)
                ->searchable()
                ->sortable(),

            Boolean::make('Is Rent', 'is_rent')
                ->filterable()
                ->sortable(),

            BelongsTo::make('Rent Customer', 'rentCustomer', Customer::class)
                ->filterable()
                ->searchable()
                ->sortable(),

            Boolean::make(__('Is Paid'), 'is_paid'),

            Number::make(__('Last Unit'), 'last_unit')
                ->sortable(),

            Number::make(__('Current Unit'), 'current_unit')
                ->sortable(),

            Number::make(__('Consumption'), 'consumption')
                ->sortable(),

            Currency::make(__('Unit Price'), 'unit_price')
                ->sortable(),

            Currency::make(__('Amount Before Discount'), 'total_before_discount')
                ->filterable()
                ->sortable(),

            Currency::make(__('Discount'), 'discount')
                ->filterable()
                ->sortable(),

            Currency::make(__('Amount'), 'total_price')
                ->filterable()
                ->sortable(),

            Date::make(__('Date'), 'date')
                ->filterable()
                ->sortable(),

            URL::make(__('Invoice'), 'receipt_url')
                ->showOnPreview()
                ->exceptOnForms(),
        ];
    }


    public function cards(NovaRequest $request): array
    {
        return [
            new TotalPaidGasAmount(),
            new MonthlyPaidGasAmount(),
            (new NovaDetachedFilters([
                (new ServiceApartmentTowerFilter())->withMeta(['width' => 'w-1/3']),
                (new ServiceApartmentLevelFilter())->withMeta(['width' => 'w-1/3']),
                (new ServiceApartmentNumberFilter())->withMeta(['width' => 'w-1/3']),
            ]))->width('full'),
        ];
    }


    public function filters(NovaRequest $request): array
    {
        return [
            new ServiceApartmentTowerFilter(),
            new ServiceApartmentLevelFilter(),
            new ServiceApartmentNumberFilter(),
        ];
    }

    public function uriKey(): string
    {
        return 'paid-gas-lens';
    }

    public function name(): string
    {
        return __('Paid Gas');
    }
}
