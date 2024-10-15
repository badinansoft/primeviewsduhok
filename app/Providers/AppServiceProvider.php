<?php

namespace App\Providers;

use App\Models\User;
use App\Settings\Settings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, function () {
            return Settings::make(storage_path('app/settings.json'));
        });
    }

    public function boot(): void
    {
        Gate::define('viewPulse', static function (User $user) {
            return in_array($user->email, config('app.developers'), true);
        });
    }
}
