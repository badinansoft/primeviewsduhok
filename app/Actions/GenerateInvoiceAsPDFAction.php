<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use function Spatie\LaravelPdf\Support\pdf;

class GenerateInvoiceAsPDFAction
{
    public function run(string $filename, string $view, array $objects): string
    {
        if(!Storage::disk('contabo')->exists('invoices/'.$filename.'.pdf'))
        {
            pdf()
                ->view($view, $objects)
                ->withBrowsershot(function (Browsershot $browsershot) {
                    $browsershot->noSandbox();
                })
                ->format('A5')
                ->disk('contabo')
                ->save('invoices/'.$filename.'.pdf');
        }

        return Storage::disk('contabo')->url('invoices/'.$filename.'.pdf');
    }
}
