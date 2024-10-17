<?php

namespace App\Jobs;

use App\Actions\CreateWaterForAllApartment;
use App\Data\WaterDataBulkCreating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBulkWaterInBackgroundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public WaterDataBulkCreating $data,
    )
    {}

    public function handle(): void
    {
        (new CreateWaterForAllApartment($this->data))->run();
    }
}
