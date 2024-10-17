<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Water;

class WaterObserver
{

    public function creating(Water $water): void
    {
        $apartment = Apartment::query()->findOrFail($water->apartment_id);
        $water->customer_id = $apartment?->customer_id;
        $water->is_rent = $apartment?->is_rent;
        $water->rent_customer_id = $apartment?->rent_customer_id;

        if($water->created_by === null) {
            $water->created_by = auth()->id();
        }
    }

    public function created(Water $water): void
    {
        $water->apartment->balance += $water->amount;
        $water->apartment->save();
    }
}
