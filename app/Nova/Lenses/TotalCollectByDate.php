<?php

namespace App\Nova\Lenses;

use App\Models\Gas;
use App\Models\Service;
use App\Nova\Filters\EndPaidAtFilterForTotalCollection;
use App\Nova\Filters\PaidByFilterForTotalCollection;
use App\Nova\Filters\SourceFilterForTotalCollection;
use App\Nova\Filters\StartPaidAtFilterForTotalCollection;
use App\Nova\Metrics\TotalCollectMoney;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;

class TotalCollectByDate extends Lens
{
    /**
     * @var array
     */
    public static $search = [];

    /**
     * @param LensRequest $request
     * @param  Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query): mixed
    {
        $services = Service::select([
            DB::raw("'Service' as source"),
            'services.id',
            'services.apartment_id',
            'customers.id as customer_id',
            'customers.name as customer_fullname',
            DB::raw("CONCAT(towers.name, '-', levels.name, '-', apartments.number) as apartment_name"),
            DB::raw('services.amount as total_amount'),
            'services.paid_at',
            'payers.name as paid_by_name',
            'paid_by'
        ])
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->join('apartments', 'services.apartment_id', '=', 'apartments.id')
            ->join('towers', 'apartments.tower_id', '=', 'towers.id')
            ->join('levels', 'apartments.level_id', '=', 'levels.id')
            ->leftJoin('users as payers', 'services.paid_by', '=', 'payers.id')
            ->whereNotNull('services.paid_at');

        $gas = Gas::query()->select([
            DB::raw("'Gas' as source"),
            'gases.id',
            'gases.apartment_id',
            'customers.id as customer_id',
            'customers.name as customer_fullname',
            DB::raw("CONCAT(towers.name, '-', levels.name, '-', apartments.number) as apartment_name"),
            DB::raw('gases.total_price as total_amount'),
            'gases.paid_at',
            'payers.name as paid_by_name',
            'paid_by'
        ])
            ->join('customers', 'gases.customer_id', '=', 'customers.id')
            ->join('apartments', 'gases.apartment_id', '=', 'apartments.id')
            ->join('towers', 'apartments.tower_id', '=', 'towers.id')
            ->join('levels', 'apartments.level_id', '=', 'levels.id')
            ->leftJoin('users as payers', 'gases.paid_by', '=', 'payers.id')
            ->whereNotNull('gases.paid_at');



        $startDate = null;
        $endDate = null;
        $source = null;
        foreach ($request->filters() as $filter) {
           if($filter->filter instanceof PaidByFilterForTotalCollection) {
               $services->where('services.paid_by', $filter->value);
               $gas->where('gases.paid_by', $filter->value);
           }
           if ($filter->filter instanceof StartPaidAtFilterForTotalCollection) {
               $startDate = $filter->value;
           }
          if ($filter->filter instanceof EndPaidAtFilterForTotalCollection) {
            $endDate = $filter->value;
          }
            if ($filter->filter instanceof SourceFilterForTotalCollection) {
                $source = $filter->value;
            }
        }

        if ($startDate && $endDate) {
            $services->whereBetween('services.paid_at', [$startDate, $endDate]);
            $gas->whereBetween('gases.paid_at', [$startDate, $endDate]);
        }

        if ($startDate && !$endDate) {
            $services->where('services.paid_at', '>=', $startDate);
            $gas->where('gases.paid_at', '>=', $startDate);
        }

        if (!$startDate && $endDate) {
            $services->where('services.paid_at', '<=', $endDate);
            $gas->where('gases.paid_at', '<=', $endDate);
        }

        if ($source['Service'] && $source['Gas']) {
            $queryA = $services->union($gas);
        }

        if (!$source['Service'] && !$source['Gas']) {
            $queryA = $services->union($gas);
        }

        if ($source['Service'] && !$source['Gas']) {
            $queryA = $services;
        }

        if (!$source['Service'] && $source['Gas']) {
            $queryA = $services->where('services.id', -1)->union($gas);
        }

        return $request->withOrdering($request->withFilters(
            $queryA
        ));
    }


    public function fields(NovaRequest $request): array
    {
        return [
           Text::make('Source', 'source'),
           Text::make('Apartment', 'apartment_name'),
           Text::make('Paid By', 'paid_by'),
           DateTime::make('Paid At', 'paid_at'),
           Currency::make('Total Amount', 'total_amount'),

        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new TotalCollectMoney,
            (new NovaDetachedFilters([
                new PaidByFilterForTotalCollection,
                new StartPaidAtFilterForTotalCollection(),
                new EndPaidAtFilterForTotalCollection(),
                new SourceFilterForTotalCollection(),
            ]))->width('full'),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new PaidByFilterForTotalCollection,
            new StartPaidAtFilterForTotalCollection(),
            new EndPaidAtFilterForTotalCollection(),
            new SourceFilterForTotalCollection(),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function uriKey(): string
    {
        return 'total-collect-by-date';
    }

    public function name(): string
    {
        return __('Total Collection');
    }

}
