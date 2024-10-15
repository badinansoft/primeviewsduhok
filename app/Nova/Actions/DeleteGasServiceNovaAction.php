<?php

namespace App\Nova\Actions;

use App\Models\Gas;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;

class DeleteGasServiceNovaAction extends DestructiveAction
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): void
    {
        if(!isset($models[0])) {
            ActionResponse::danger('No Gas services found');
        }

        $gas = Gas::query()->findOrFail($models[0]->id);

        DB::beginTransaction();
        try {
            $apartment = $gas->apartment;
            if (!$gas->is_paid) {
                $apartment->balance -= $gas->total_price;
            }
            $apartment->gas_unit -= $gas->consumption;
            $apartment->save();

            $gas->delete();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            ActionResponse::danger('Failed to delete Gas services');
        }
    }

  public function name(): string
  {
      return 'Delete Gas Service';
  }
}
