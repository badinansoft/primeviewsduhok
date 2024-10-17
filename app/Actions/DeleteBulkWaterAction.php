<?php

namespace App\Actions;

use App\Models\Water;
use Carbon\Carbon;

class DeleteBulkWaterAction
{
    public function run(int $towerId, Carbon $startDate, Carbon $endDate): int
    {
        $waters =  Water::query()
                    ->whereHas('apartment', function ($query) use ($towerId) {
                        $query->where('tower_id', $towerId);
                    })
                    ->where('start_date', '>=', $startDate)
                    ->where('end_date', '<=', $endDate)
                    ->get();

        foreach ($waters as $water) {
            $apartment = $water->apartment;
            if(!$water->is_paid) {
                $apartment->balance_usd -= $water->amount;
            }
            $apartment->save();
            $water->delete();
        }

        return $water->count();
    }
}
