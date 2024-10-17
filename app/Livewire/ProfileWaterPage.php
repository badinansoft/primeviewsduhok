<?php

namespace App\Livewire;

use App\Models\Apartment;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileWaterPage extends Component
{
    public Apartment $apartment;

    public function mount(string $uuid): void
    {
        $this->apartment = Apartment::query()->with('waters')->where('uuid', $uuid)->firstOrFail();
    }

    public function render():  View|Closure|string
    {
        return view('livewire.profile-water-page')
            ->layout('components.layouts.app', [
                'title' => 'ماء ',
                'apartment' => $this->apartment,
            ]);
    }
}
