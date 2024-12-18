<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Gas;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Nova;

class MonthlyUnPaidGasAmount extends Trend
{
    public $refreshWhenFiltersChange = true;
    public $refreshWhenActionRuns = true;

    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->sumByMonths($request, Gas::query()->whereNull('paid_at'), 'total_price', 'date')
                    ->showSumValue()
                    ->prefix(' IQD ')
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
        return 'monthly-un-paid-gas-amount';
    }

    public function name(): string
    {
        return __('Monthly Unpaid Gas Amount');
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
