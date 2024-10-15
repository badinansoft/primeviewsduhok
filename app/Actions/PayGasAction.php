<?php

namespace App\Actions;

use App\Models\Gas;
use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PayGasAction
{
    /**
     * @throws Exception
     */
    public function execute(Gas $gas): true
    {
        DB::beginTransaction();

        if($gas->isPaid) {
            throw new RuntimeException('The gas bill has already been paid.');
        }

        try {
            $gas->paid_at = now();
            $gas->paid_by = auth()->id();
            $gas->save();

            $gas->apartment->balance -= $gas->total_price;
            $gas->apartment->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
