<?php

namespace App\Nova;

use App\Enums\UserRoles;
use App\Nova\Actions\DeleteGasServiceNovaAction;
use App\Nova\Actions\PayGasNovaAction;
use App\Nova\Filters\ServiceApartmentLevelFilter;
use App\Nova\Filters\ServiceApartmentNumberFilter;
use App\Nova\Filters\ServiceApartmentTowerFilter;
use App\Nova\Lenses\PaidGasLens;
use App\Nova\Metrics\MonthlyGasConsumptionUnit;
use App\Nova\Metrics\MonthlyUnPaidGasAmount;
use App\Nova\Metrics\TotalUnPaidGasAmount;
use App\Trait\WithoutReplicationAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class Gas extends Resource
{
    use WithoutReplicationAction;

    public static string $model = \App\Models\Gas::class;

    /**
     * @var string
     */
    public static $title = 'id';

    /**
     * @var array
     */
    public static $search = [
        'id',
        'customer.name',
        'customer.phone',
    ];

    public static $with = [
        'apartment',
        'customer',
        'rentCustomer',
    ];

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

            DateTime::make(__('Paid At'), 'paid_at')
                ->hideFromIndex(),

            Textarea::make(__('Notes'), 'notes')
                ->hideFromIndex(),

            BelongsTo::make(__('Paid By'), 'paidBy', User::class)
                ->hideFromIndex(),

            BelongsTo::make(__('Created By'), 'createdBy', User::class)
                ->hideFromIndex(),

            URL::make(__('Invoice'), 'receipt_url')
                ->showOnPreview()
                ->exceptOnForms(),

            File::make(__('Attachment'), 'attachment')
                ->disk('public')
                ->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            (new TotalUnPaidGasAmount),
            (new MonthlyUnPaidGasAmount()),
            (new MonthlyGasConsumptionUnit),
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

    public function lenses(NovaRequest $request): array
    {
        return [
            new PaidGasLens()
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new PayGasNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN || $request->user()->role === UserRoles::NORMAL_USER)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->onlyInline(),

            (new DeleteGasServiceNovaAction())
                ->canSee(fn() => $request->user()->role === UserRoles::ADMIN)
                ->canRun(fn() => true)
                ->onlyOnDetail()
                ->onlyInline(),
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

    public function authorizedToUpdate(Request $request): false
    {
        return false;
    }

    public function authorizedToDelete(Request $request): false
    {
        return false;
    }
}
