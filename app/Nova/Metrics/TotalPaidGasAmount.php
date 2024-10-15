<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Gas;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class TotalPaidGasAmount extends Value
{
    public $refreshWhenFiltersChange = true;
    public $refreshWhenActionRuns = true;

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->sum($request, Gas::query()->whereNotNull('paid_at'), 'total_price')
                ->prefix(' IQD ')
                ->format([
                    'thousandSeparated' => true,
                    'mantissa' => 0,
                ]);
    }

    public function ranges(): array
    {
        return [
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            365 => Nova::__('365 Days'),
            'ALL' => Nova::__('All'),
        ];
    }

    public function uriKey(): string
    {
        return 'total-paid-gas-amount';
    }

    public function name(): string
    {
        return __('Total paid Gas Amount');
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
