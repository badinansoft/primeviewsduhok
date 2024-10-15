<?php

namespace App\Providers;

use App\Enums\UserRoles;
use App\Nova\Dashboards\Main;
use App\Nova\Menu\MainMenu;
use Badinansoft\Bigcheckbox\Bigcheckbox;
use Bakerkretzmar\NovaSettingsTool\SettingsTool;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{

    public function boot(): void
    {
        parent::boot();

        Nova::withBreadcrumbs();
        Nova::showUnreadCountInNotificationCenter();


        Nova::mainMenu(static function () {
            return (new MainMenu())();
        });

        Nova::footer(static function () {
            return view('footer')->render();
        });
    }

    protected function routes(): void
    {
        Nova::routes()
                ->withAuthenticationRoutes(default: true);
    }

    protected function gate(): void
    {
        Gate::define('viewNova', static function ($user) {
            return true;
        });
    }

    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }

    public function tools(): array
    {
        return [
            (new SettingsTool)->canSee(static function ($request) {
                return $request->user()->role === UserRoles::ADMIN;
            }),
            new Bigcheckbox(),
        ];
    }

    public function register(): void
    {
        //
    }
}
