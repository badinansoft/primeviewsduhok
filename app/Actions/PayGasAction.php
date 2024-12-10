<?php

namespace App\Actions;

use App\Jobs\SendNotificationToCustomerViaWhatsAppJob;
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

            $message = "
                     ✅ تم دفع فاتورتك الغاز بنجاح
                    قيمة فاتورة الغاز: {$gas->total_price}  د.ع
                    تاريخ الدفع: {$gas->paid_at->format('Y-m-d')}
            ";

            dispatch(new SendNotificationToCustomerViaWhatsAppJob($message, $gas->apartment));

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
