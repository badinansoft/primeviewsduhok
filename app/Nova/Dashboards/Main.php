<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ApartmentCount;
use App\Nova\Metrics\ApartmentRentPartition;
use App\Nova\Metrics\ApartmentViewPartition;
use App\Nova\Metrics\MonthlyGasConsumptionUnit;
use App\Nova\Metrics\MonthlyPaidGasAmount;
use App\Nova\Metrics\MonthlyPaidServiceAmount;
use App\Nova\Metrics\MonthlyUnPaidGasAmount;
use App\Nova\Metrics\MonthlyUnPaidServiceAmount;
use App\Nova\Metrics\TotalPaidServiceAmount;
use App\Nova\Metrics\TotalPaidWaterAmount;
use App\Nova\Metrics\TotalUnPaidServiceAmount;
use App\Nova\Metrics\TotalUnPaidWaterAmount;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function cards(): array
    {
        return [
            new ApartmentCount(),
            new ApartmentRentPartition(),
            new ApartmentViewPartition(),
            new MonthlyPaidServiceAmount(),
            new MonthlyUnPaidServiceAmount(),
            new MonthlyPaidGasAmount(),
            (new MonthlyUnPaidGasAmount()),
            (new MonthlyGasConsumptionUnit),
            (new TotalPaidWaterAmount()),
            (new TotalUnPaidWaterAmount()),
        ];
    }
}
