<?php

namespace App\Nova\Filters;

use App\Models\Level;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ServiceApartmentLevelFilter extends Filter
{

    public $component = 'select-filter';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereHas('apartment', function ($query) use ($value) {
            $query->where('level_id', $value);
        });
    }

    public function options(NovaRequest $request): array
    {
        $levels = Level::query()->get()->pluck('id', 'title')->toArray();
        uksort($levels, 'strnatcmp');

        return $levels;
    }

    public function name(): string
    {
        return __('Apartment Level');
    }
}
