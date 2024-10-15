<?php

namespace App\Nova\Metrics;

use App\Enums\UserRoles;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class ApartmentRentPartition extends Partition
{
    public $refreshWhenActionRuns = true;
    public $refreshWhenFiltersChange = true;

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, Apartment::class, 'is_rent')->label(function ($value) {
            return $value ? 'Rent' : 'Not Rent';
        });
    }

    public function uriKey(): string
    {
        return 'apartment-rent-partition';
    }

    public function authorizedToSee(Request $request): bool
    {
        return $request->user()->role !== UserRoles::NORMAL_USER;
    }
}
