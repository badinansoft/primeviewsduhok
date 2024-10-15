<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Gas;
use App\Models\Service;
use App\Nova\Filters\EndPaidAtFilterForTotalCollection;
use App\Nova\Filters\PaidByFilterForTotalCollection;
use App\Nova\Filters\SourceFilterForTotalCollection;
use App\Nova\Filters\StartPaidAtFilterForTotalCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class TotalCollectMoney extends Value
{
    public $refreshWhenActionRuns = true;
    public $refreshWhenFiltersChange = true;

    public function calculate(NovaRequest $request): ValueResult
    {

        $services = Service::select([
            DB::raw('services.amount as total_amount'),
            'services.paid_at',
            'paid_by'
        ])
            ->whereNotNull('services.paid_at');

        $gas = Gas::query()->select([
            DB::raw('gases.total_price as total_amount'),
            'gases.paid_at',
            'paid_by'
        ])
            ->whereNotNull('gases.paid_at');


        $filters = json_decode(base64_decode($request->filter), true);

        $startDate = null;
        $endDate = null;
        $source = null;

        foreach ($filters as $filter) {
            if(isset($filter[PaidByFilterForTotalCollection::class]) && $filter[PaidByFilterForTotalCollection::class] !== '') {
                $services->where('services.paid_by', $filter[PaidByFilterForTotalCollection::class]);
                $gas->where('gases.paid_by', $filter[PaidByFilterForTotalCollection::class]);
            }
            if (isset($filter[StartPaidAtFilterForTotalCollection::class]) && $filter[StartPaidAtFilterForTotalCollection::class] !== '') {
                $startDate = $filter[StartPaidAtFilterForTotalCollection::class];
            }
            if (isset($filter[EndPaidAtFilterForTotalCollection::class]) && $filter[EndPaidAtFilterForTotalCollection::class] !== '') {
                $endDate = $filter[EndPaidAtFilterForTotalCollection::class];
            }
            if (isset($filter[SourceFilterForTotalCollection::class]) && $filter[SourceFilterForTotalCollection::class] !== '') {
                $source = $filter[SourceFilterForTotalCollection::class];
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
            $queryA = $services->union($gas->where('gases.id', -1));
        }

        if (!$source['Service'] && $source['Gas']) {
            $queryA = $services->where('services.id', -1)->union($gas);
        }

        return $this->sum($request, $queryA, 'total_amount')
            ->format([
                'thousandSeparated' => true,
                'mantissa' => 0,
            ]);
    }

    public function ranges(): array
    {
        return [
            'ALL' => Nova::__('All'),
        ];
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }

}
