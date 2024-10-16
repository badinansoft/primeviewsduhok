<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Service;

class ServiceObserver
{
    public function creating(Service $service): void
    {
        $apartment = Apartment::query()->findOrFail($service->apartment_id);
        $service->customer_id = $apartment->customer_id;
        $service->is_rent = $apartment->is_rent;
        $service->rent_customer_id = $apartment->rent_customer_id;

        if($service->created_by === null) {
            $service->created_by = auth()->id();
        }
    }

    public function created(Service $service): void
    {
        $service->apartment->balance_usd += $service->amount;
        $service->apartment->save();
    }
}
