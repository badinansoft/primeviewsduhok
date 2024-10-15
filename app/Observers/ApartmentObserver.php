<?php

namespace App\Observers;

use App\Models\Apartment;
use Illuminate\Support\Str;

class ApartmentObserver
{
    public function creating(Apartment $apartment): void
    {
        $apartment->uuid = Str::uuid();
    }
}
