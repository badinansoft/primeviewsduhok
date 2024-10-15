<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class SourceFilterForTotalCollection extends BooleanFilter
{

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query;
    }

    /**
     * @param NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request): array
    {
        return [
            'service' => __('Service'),
            'gas' => __('Gas'),
        ];
    }

    public function name(): string
    {
        return __('Source');
    }
}
