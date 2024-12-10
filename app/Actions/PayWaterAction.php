<?php

namespace App\Actions;

use App\Jobs\SendNotificationToCustomerViaWhatsAppJob;
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
            $message = "
                     ✅ تم دفع فاتورتك خدمات الماء بنجاح
                    قيمة فاتورة الغاز: {$water->amount}  د.ع
                    تاريخ الدفع: {$water->paid_at->format('Y-m-d')}
            ";

            dispatch(new SendNotificationToCustomerViaWhatsAppJob($message, $water->apartment));
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
