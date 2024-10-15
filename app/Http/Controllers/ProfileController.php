<?php

namespace App\Http\Controllers;

use App\Models\Apartment;

class ProfileController extends Controller
{
    public function show(string $uuid)
    {
        $apartment = Apartment::query()->where('uuid', $uuid)->firstOrFail();
        return view('profile.show', compact('apartment'));
    }
}
