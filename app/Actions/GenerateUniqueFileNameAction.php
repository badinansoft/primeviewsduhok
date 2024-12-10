<?php

namespace App\Actions;

use App\Models\Gas;
use App\Models\Service;
use App\Models\Water;

class GenerateUniqueFileNameAction
{
    public function run(Service|Water|Gas $model): string
    {
        $modelType = match (true) {
            $model instanceof Service => 'service',
            $model instanceof Water => 'water',
            $model instanceof Gas => 'gas',
            default => 'unknown',
        };

        return $modelType . '-' . $model->id . '-' . $model->created_at->year . '-' . $model->created_at->month;
    }
}
