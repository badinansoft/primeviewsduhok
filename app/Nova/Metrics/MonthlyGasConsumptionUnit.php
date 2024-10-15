<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Gas;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Nova;

class MonthlyGasConsumptionUnit extends Trend
{
    public $refreshWhenFiltersChange = true;
    public $refreshWhenActionRuns = true;

    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->sumByMonths($request, Gas::query(), 'consumption', 'date')
            ->showSumValue()
            ->prefix(' Unit ')
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
        return 'monthly-gas-consumption-unit';
    }

    public function name(): string
    {
        return __('Monthly Gas Consumption Unit');
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
