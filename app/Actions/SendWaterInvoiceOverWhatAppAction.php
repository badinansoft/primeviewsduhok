<?php

namespace App\Actions;

use App\Models\Water;

class SendWaterInvoiceOverWhatAppAction extends SendInvoiceOverWhatAppAction
{
    public function run(Water $water): void
    {
        $fileName = $this->generateUniqueFileNameAction->run($water);

        // Generate invoice as PDF
        $fileUrl = $this->generateInvoiceAsPDFAction->run($fileName, 'print.water', ['water' => $water]);

        if(isset($water->rentCustomer)){
            $phoneNumber = $water->rentCustomer->phone;
        } else {
            $phoneNumber = $water->customer->phone;
        }

        $phoneNumber = '964' . $phoneNumber .'@c.us';
        $caption = ' فاتورة خدمات ماء ل شهر ' . $water->start_date->month . ' - ' . $water->start_date->year;

        // send invoice over WhatsApp
        $this->waAPISendMessageAction->sendPdfFileAsMessage($phoneNumber, $fileUrl, $caption);
    }
}
