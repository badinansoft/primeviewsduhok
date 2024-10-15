<?php

namespace App\Actions;

use App\Data\ServiceDataBulkCreating;
use App\Models\Apartment;
use App\Models\Service;

class CreateSingleApartmentServiceAction
{
    public function execute(Apartment $apartment, ServiceDataBulkCreating $data): void
    {
        $service = new Service();
        $service->apartment_id = $apartment->id;
        $service->amount = $data->amount;
        $service->notes = $data->note;
        $service->start_date = $data->startDate;
        $service->end_date = $data->endDate;
        $service->created_by = $data->createdBy;
        $service->save();
    }
}
