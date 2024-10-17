<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class WaterIsPaidFilter extends BooleanFilter
{

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        $paid = $value['paid'] ?? false;
        $unpaid = $value['unpaid'] ?? false;

        if ($paid && $unpaid) {
            return $query;
        }

        if ($paid) {
            return $query->whereNotNull('paid_at');
        }

        if ($unpaid) {
            return $query->whereNull('paid_at');
        }

        return $query;
    }


    public function options(NovaRequest $request): array
    {
        return [
            __('Paid') => 'paid',
            __('Unpaid') => 'unpaid',
        ];
    }
}
