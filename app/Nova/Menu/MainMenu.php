<?php

namespace App\Nova\Menu;

use App\Enums\UserRoles;
use App\Nova\Apartment;
use App\Nova\Customer;
use App\Nova\Dashboards\Main;
use App\Nova\Gas;
use App\Nova\Lenses\PaidGasLens;
use App\Nova\Lenses\PaidServiceLens;
use App\Nova\Lenses\TotalCollectByDate;
use App\Nova\Level;
use App\Nova\Service;
use App\Nova\Tower;
use App\Nova\User;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;

class MainMenu
{
    public function __invoke(): array
    {
        return [
            MenuSection::dashboard(Main::class)
                ->icon('chart-bar'),

            MenuSection::resource(User::class)
                ->icon('users'),

            MenuSection::resource(Service::class)
                ->icon('trash'),

            MenuSection::lens(Service::class, PaidServiceLens::class)
                ->icon('cash'),

            MenuSection::resource(Gas::class)
                ->icon('fire'),

            MenuSection::lens(Gas::class, PaidGasLens::class)
                ->icon('cash'),

            MenuSection::resource(Apartment::class)
                ->icon('office-building'),

            MenuSection::lens(Apartment::class, TotalCollectByDate::class)
                ->canSee(static function ($request) {
                    return $request->user()->role !== UserRoles::NORMAL_USER;
                })
                ->icon('cash'),

            MenuSection::make(__('Definitions'), [
                MenuItem::resource(Tower::class),
                MenuItem::resource(Level::class),
                MenuItem::resource(Customer::class),
            ])->icon('pencil'),

            MenuSection::make(__(config('nova-settings-tool.sidebar-label', 'Settings')))
                ->canSee(static function ($request) {
                    return $request->user()->role === UserRoles::ADMIN;
                })
                ->path('/settings')
                ->icon('cog')
        ];
    }
}
