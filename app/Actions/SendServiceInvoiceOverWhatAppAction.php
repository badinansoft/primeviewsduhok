<?php

namespace App\Actions;

use App\Models\Service;

class SendServiceInvoiceOverWhatAppAction extends SendInvoiceOverWhatAppAction
{

    public function run(Service $service): void
    {
        $fileName = $this->generateUniqueFileNameAction->run($service);

        // Generate invoice as PDF
        $fileUrl = $this->generateInvoiceAsPDFAction->run($fileName, 'print.service', ['service' => $service]);

        if(isset($service->rentCustomer)){
            $phoneNumber = $service->rentCustomer->phone;
        } else {
            $phoneNumber = $service->customer->phone;
        }

        $phoneNumber = '964' . $phoneNumber .'@c.us';
        $caption = ' فاتورة خدمات ل شهر ' . $service->start_date->month . ' - ' . $service->start_date->year;

        // send invoice over WhatsApp
        $this->waAPISendMessageAction->sendPdfFileAsMessage($phoneNumber, $fileUrl, $caption);
    }
}
