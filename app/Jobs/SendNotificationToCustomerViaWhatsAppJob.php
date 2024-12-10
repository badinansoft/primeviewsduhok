<?php

namespace App\Jobs;

use App\Actions\SendTextMessageToApartment;
use App\Models\Apartment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToCustomerViaWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $message, private readonly Apartment $apartment)
    {
        $this->queue = 'whatsapp';
    }

    public function handle(): void
    {
        resolve(SendTextMessageToApartment::class)->run($this->message, $this->apartment);
    }

    public function middleware(): array
    {
        return [new EnforceFiveSecondDelay];
    }
}
