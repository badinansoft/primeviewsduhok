<?php

namespace App\Nova\Filters;

use App\Models\Apartment;
use App\Models\Level;
use App\Models\Tower;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class WaterApartmentTowerFilter extends Filter
{
    public $component = 'select-filter';

    public function apply(NovaRequest $request,$query, $value): Builder
    {
        return $query->whereHas('apartment', function ($query) use ($value) {
            $query->where('tower_id', $value);
        });
    }

    public function options(NovaRequest $request): array
    {
        $towers = Tower::query()->get()->pluck('id', 'name')->toArray();
        uksort($towers, 'strnatcmp');
        return $towers;
    }

    public function name(): string
    {
        return __('Apartment Tower');
    }
}
