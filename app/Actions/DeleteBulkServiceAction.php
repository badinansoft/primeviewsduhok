<?php

namespace App\Actions;

use App\Models\Service;
use Carbon\Carbon;

class DeleteBulkServiceAction
{
    public function run(int $towerId, Carbon $startDate, Carbon $endDate): int
    {
        $services =  Service::query()
                    ->whereHas('apartment', function ($query) use ($towerId) {
                        $query->where('tower_id', $towerId);
                    })
                    ->where('start_date', '>=', $startDate)
                    ->where('end_date', '<=', $endDate)
                    ->get();

        foreach ($services as $service) {
            $apartment = $service->apartment;
            if(!$service->is_paid) {
                $apartment->balance_usd -= $service->amount;
            }
            $apartment->save();
            $service->delete();
        }

        return $services->count();
    }
}
