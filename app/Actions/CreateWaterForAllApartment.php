<?php

namespace App\Actions;

use App\Data\WaterDataBulkCreating;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Water;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class CreateWaterForAllApartment
{
    private User $user;
    public function __construct(
        readonly WaterDataBulkCreating $data,
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
                        ->message("Water already exists for apartment {$apartment->title} in the given period")
                        ->action('View Apartment', URL::remote("/portal/resources/apartments/{$apartment->id}"))
                        ->icon('exclamation-triangle')
                        ->type('warning')
                );
                continue;
            }

            $water = new Water();
            $water->apartment_id = $apartment->id;
            $water->amount = $this->data->amount;
            $water->notes = $this->data->note;
            $water->start_date = $this->data->startDate;
            $water->end_date = $this->data->endDate;
            $water->created_by = $this->data->createdBy;
            $water->save();
        }
    }

    private function checkIfServiceExistsInPeriod(Apartment $apartment): bool
    {
        return $apartment->waters()->where('start_date', '<=', $this->data->startDate)
            ->where('end_date', '>=', $this->data->endDate)
            ->exists();
    }
}
