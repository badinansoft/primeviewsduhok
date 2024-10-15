<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class ApartmentCount extends Value
{
    public $refreshWhenActionRuns = true;
    public $refreshWhenFiltersChange = true;

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, Apartment::class)
                    ->format([
                        'thousandSeparated' => true,
                        'mantissa' => 0,
                    ]);
    }

    public function ranges(): array
    {
        return [
            'ALL' => Nova::__('All'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            365 => Nova::__('365 Days'),
            'TODAY' => Nova::__('Today'),
            'MTD' => Nova::__('Month To Date'),
            'QTD' => Nova::__('Quarter To Date'),
            'YTD' => Nova::__('Year To Date'),
        ];
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
