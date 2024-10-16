<?php

namespace App\Actions;

use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\DB;

class PayServiceAction
{
    /**
     * @throws Exception
     */
    public function execute(Service $service): true
    {
        DB::beginTransaction();

        try {
            $service->paid_at = now();
            $service->paid_by = auth()->id();
            $service->save();

            $service->apartment->balance_usd -= $service->amount;
            $service->apartment->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
