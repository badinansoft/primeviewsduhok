<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Service;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class TotalPaidServiceAmount extends Value
{
    public $refreshWhenFiltersChange = true;
    public $refreshWhenActionRuns = true;

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->sum($request, Service::query()->whereNotNull('paid_at'), 'amount')
                    ->prefix(' $ ')
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
        return 'total-paid-service-amount';
    }

    public function name(): string
    {
        return __('Total Paid Service Amount');
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
