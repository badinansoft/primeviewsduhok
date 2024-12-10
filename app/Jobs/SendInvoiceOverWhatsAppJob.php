<?php

namespace App\Jobs;

use App\Actions\SendGasInvoiceOverWhatAppAction;
use App\Actions\SendServiceInvoiceOverWhatAppAction;
use App\Actions\SendWaterInvoiceOverWhatAppAction;
use App\Models\Gas;
use App\Models\Service;
use App\Models\Water;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInvoiceOverWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Service|Water|Gas $model)
    {
        $this->queue = 'whatsapp';
    }

    public function handle(): void
    {
        // Send invoice over WhatsApp
        if($this->model instanceof Gas){
            resolve(SendGasInvoiceOverWhatAppAction::class)->run($this->model);
        } elseif($this->model instanceof Water){
            resolve(SendWaterInvoiceOverWhatAppAction::class)->run($this->model);
        } elseif($this->model instanceof Service){
            resolve(SendServiceInvoiceOverWhatAppAction::class)->run($this->model);
        }
    }

    public function middleware(): array
    {
        return [new EnforceFiveSecondDelay];
    }
}
