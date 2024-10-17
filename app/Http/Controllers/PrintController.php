<?php

namespace App\Http\Controllers;

use App\Models\Gas;
use App\Models\Service;
use App\Models\Water;

class PrintController extends Controller
{
    public function service(Service $service)
    {
        return view('print.service', compact('service'));
    }

    public function gas(Gas $gas)
    {
        return view('print.gas', compact('gas'));
    }

    public function water(Water $water)
    {
        return view('print.water', compact('water'));
    }

    public function gasProfile(string $uuid, int $id)
    {
        $gas = Gas::query()->where('id', $id)->whereHas('apartment', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->firstOrFail();
        $isCustomerView = true;

        return view('print.gas', compact('gas', 'isCustomerView'));
    }

    public function serviceProfile(string $uuid, int $id)
    {
        $service = Service::query()->where('id', $id)->whereHas('apartment', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->firstOrFail();
        $isCustomerView = true;
        return view('print.service', compact('service', 'isCustomerView'));
    }

    public function waterProfile(string $uuid, int $id)
    {
        $water = Water::query()->where('id', $id)->whereHas('apartment', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->firstOrFail();
        $isCustomerView = true;
        return view('print.water', compact('water', 'isCustomerView'));
    }
}
