<?php

namespace App\Livewire;

use App\Models\Apartment;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileHomePage extends Component
{

    public Apartment $apartment;

    public function mount(string $uuid): void
    {
        $this->apartment = Apartment::query()->where('uuid', $uuid)->firstOrFail();
    }

    public function render():  View|Closure|string
    {
        return view('livewire.profile-home-page')
                ->layout('components.layouts.app', [
                    'title' => 'بروفايل الشقة',
                    'apartment' => $this->apartment,
                ]);
    }
}
