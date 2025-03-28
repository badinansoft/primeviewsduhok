<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Service;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Nova;

class MonthlyUnPaidServiceAmount extends Trend
{
    public $refreshWhenFiltersChange = true;
    public $refreshWhenActionRuns = true;

    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->sumByMonths($request, Service::query()->whereNull('paid_at'), 'amount', 'start_date')
            ->showSumValue()
            ->prefix(' $ ')
            ->format([
                'thousandSeparated' => true,
                'mantissa' => 0,
            ]);
    }

    public function ranges(): array
    {
        return [
            12 => Nova::__('12 Months'),
            24 => Nova::__('2 Years'),
            60 => Nova::__('5 Years'),
        ];
    }

    public function uriKey(): string
    {
        return 'monthly-un-paid-service-amount';
    }

    public function name(): string
    {
        return __('Monthly Unpaid Service Amount');
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
