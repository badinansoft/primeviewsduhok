<?php

namespace App\Actions;

use App\Models\Water;
use Exception;
use Illuminate\Support\Facades\DB;

class PayWaterAction
{
    /**
     * @throws Exception
     */
    public function execute(Water $water): true
    {
        DB::beginTransaction();

        try {
            $water->paid_at = now();
            $water->paid_by = auth()->id();
            $water->save();

            $water->apartment->balance -= $water->amount;
            $water->apartment->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
