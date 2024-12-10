<?php

namespace App\Actions;

use App\Data\ServiceDataBulkCreating;
use App\Jobs\SendInvoiceOverWhatsAppJob;
use App\Models\Apartment;
use App\Models\Service;
use App\Models\User;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class CreateServiceForAllApartment
{
    private User $user;

    public function __construct(
        readonly ServiceDataBulkCreating $data,
    )
    {
        $this->user = User::find($this->data->createdBy);
    }

    public function run(): void
    {
        $areas = [];
        foreach ($this->data->area as $key => $value)
        {
            if($value) {
                $areas[] = $key;
            }
        }

        $apartments = Apartment::query()
                                ->where('tower_id', $this->data->towerId)
                                ->whereIn('area', $areas)
                                ->get();


        foreach ($apartments as $apartment)
        {
            if (!$apartment->status) {
                continue;
            }

            if ($this->checkIfServiceExistsInPeriod($apartment))
            {
                $this->user->notify(
                    NovaNotification::make()
                        ->message("Service already exists for apartment {$apartment->title} in the given period")
                        ->action('View Apartment', URL::remote("/portal/resources/apartments/{$apartment->id}"))
                        ->icon('exclamation-triangle')
                        ->type('warning')
                );
                continue;
            }

            $service = new Service();
            $service->apartment_id = $apartment->id;
            $service->amount = $this->data->amount;
            $service->notes = $this->data->note;
            $service->start_date = $this->data->startDate;
            $service->end_date = $this->data->endDate;
            $service->created_by = $this->data->createdBy;
            $service->save();
            dispatch(new SendInvoiceOverWhatsAppJob($service));
        }
    }

    private function checkIfServiceExistsInPeriod(Apartment $apartment): bool
    {
        return $apartment->services()->where('start_date', '<=', $this->data->startDate)
            ->where('end_date', '>=', $this->data->endDate)
            ->exists();
    }
}
