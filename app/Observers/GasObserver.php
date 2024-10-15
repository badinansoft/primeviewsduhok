<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Gas;

class GasObserver
{
    public function creating(Gas $gas): void
    {
        $apartment = Apartment::query()->findOrFail($gas->apartment_id);
        $gas->customer_id = $apartment->customer_id;
        $gas->is_rent = $apartment->is_rent;
        $gas->rent_customer_id = $apartment->rent_customer_id;

        $gas->last_unit = $apartment->gas_unit;
        $gas->consumption = $gas->current_unit - $gas->last_unit;
        $gas->total_before_discount = $gas->consumption * $gas->unit_price;
        $gas->total_price = $gas->total_before_discount - $gas->discount;

        if($gas->created_by === null) {
            $gas->created_by = auth()->id();
        }
    }

    public function created(Gas $gas): void
    {
        if(!$gas->is_paid) {
            $gas->apartment->balance += $gas->total_price;
        }
        $gas->apartment->gas_unit = $gas->current_unit;
        $gas->apartment->save();
    }
}
