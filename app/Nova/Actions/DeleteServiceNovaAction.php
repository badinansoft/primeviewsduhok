<?php

namespace App\Nova\Actions;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;

class DeleteServiceNovaAction extends DestructiveAction
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): void
    {
        if(!isset($models[0])) {
            ActionResponse::danger('No Gas services found');
        }

        $service = Service::query()->findOrFail($models[0]->id);

        DB::beginTransaction();
        try {
            if(!$service->is_paid) {
                $apartment = $service->apartment;
                $apartment->balance_usd -= $service->amount;
                $apartment->save();
            }
            $service->delete();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            ActionResponse::danger('Failed to delete services');
        }
    }

    public function name(): string
    {
        return 'Delete Service';
    }

}
