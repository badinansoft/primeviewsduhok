<?php

namespace App\Actions;

use App\Jobs\SendNotificationToCustomerViaWhatsAppJob;
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

            $message = "
                     ✅ تم دفع فاتورتك خدمات بنجاح
                    قيمة فاتورة الغاز: {$service->amount} $
                    تاريخ الدفع: {$service->paid_at->format('Y-m-d')}
            ";

            dispatch(new SendNotificationToCustomerViaWhatsAppJob($message, $service->apartment));
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
