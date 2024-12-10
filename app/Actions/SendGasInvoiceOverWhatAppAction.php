<?php

namespace App\Actions;

use App\Models\Gas;

class SendGasInvoiceOverWhatAppAction extends SendInvoiceOverWhatAppAction
{
    public function run(Gas $gas): void
    {
        $fileName = $this->generateUniqueFileNameAction->run($gas);

        // Generate invoice as PDF
        $fileUrl = $this->generateInvoiceAsPDFAction->run($fileName, 'print.gas', ['gas' => $gas]);

        if(isset($gas->rentCustomer)){
            $phoneNumber = $gas->rentCustomer->phone;
        } else {
            $phoneNumber = $gas->customer->phone;
        }

        $phoneNumber = '964' . $phoneNumber .'@c.us';
        $caption = ' فاتورة الغاز ل شهر ' . $gas->date->month . ' - ' . $gas->date->year;



        // send invoice over WhatsApp
        $this->waAPISendMessageAction->sendPdfFileAsMessage($phoneNumber, $fileUrl, $caption);
    }
}
