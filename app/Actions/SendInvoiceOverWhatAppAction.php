<?php

namespace App\Actions;

abstract class SendInvoiceOverWhatAppAction
{
    public function __construct(
        protected GenerateInvoiceAsPDFAction $generateInvoiceAsPDFAction,
        protected WaAPISendMessageAction $waAPISendMessageAction,
        protected GenerateUniqueFileNameAction $generateUniqueFileNameAction,
    )
    {}

}
