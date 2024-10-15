<?php

namespace App\Jobs;

use App\Actions\CreateServiceForAllApartment;
use App\Data\ServiceDataBulkCreating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBulkServiceInBackgroundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ServiceDataBulkCreating $data,
    )
    {}

    public function handle(): void
    {
        (new CreateServiceForAllApartment($this->data))->run();
    }
}
