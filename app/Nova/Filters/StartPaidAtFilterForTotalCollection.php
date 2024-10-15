<?php

namespace App\Nova\Filters;

use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class StartPaidAtFilterForTotalCollection extends DateFilter
{
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query;
    }

    public function name(): string
    {
        return __('Start Paid At');
    }
}
