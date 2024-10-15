<?php

namespace App\Livewire;

use App\Models\Apartment;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileGasPage extends Component
{
    public Apartment $apartment;

    public function mount(string $uuid): void
    {
        $this->apartment = Apartment::query()->with('gas')->where('uuid', $uuid)->firstOrFail();
    }

    public function render():  View|Closure|string
    {
        return view('livewire.profile-gas-page')
            ->layout('components.layouts.app', [
                'title' => 'عن قرية فرنسية ٢',
                'apartment' => $this->apartment,
            ]);
    }
}
