<?php

namespace App\Nova\Actions;

use App\Models\Water;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;

class DeleteWaterNovaAction extends DestructiveAction
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): void
    {
        if(!isset($models[0])) {
            ActionResponse::danger('No Water services found');
        }

        $water = Water::query()->findOrFail($models[0]->id);

        DB::beginTransaction();
        try {
            if(!$water?->is_paid) {
                $apartment = $water?->apartment;
                $apartment->balance -= $water?->amount;
                $apartment->save();
            }
            $water?->delete();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            ActionResponse::danger('Failed to delete Water');
        }
    }

    public function name(): string
    {
        return 'Delete Water';
    }

}
