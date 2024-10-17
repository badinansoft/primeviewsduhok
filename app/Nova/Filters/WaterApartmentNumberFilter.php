<?php

namespace App\Nova\Filters;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class WaterApartmentNumberFilter extends Filter
{
    public $component = 'select-filter';


    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereHas('apartment', function ($query) use ($value) {
            $query->where('number', $value);
        });
    }

    public function options(NovaRequest $request): array
    {
        $maxApartmentNumber = Apartment::query()->max('number');

        return range(1, $maxApartmentNumber);
    }

    public function name(): string
    {
        return __('Apartment Number');
    }
}
