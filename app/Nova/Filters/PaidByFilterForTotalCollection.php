<?php

namespace App\Nova\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaidByFilterForTotalCollection extends Filter
{
    /**
     * @var string
     */
    public $component = 'select-filter';

    /**
     * @param NovaRequest $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query;
    }


    public function options(NovaRequest $request): array
    {
        return User::query()->get()->pluck('id', 'name')->toArray();
    }

    public function name(): string
    {
        return __('Paid By');
    }
}
