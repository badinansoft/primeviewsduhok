<?php

namespace App\Actions;

use App\Models\Apartment;

readonly class SendTextMessageToApartment
{
    public function __construct(private WaAPISendMessageAction $waAPISendMessageAction)
    {}

    public function run(string $message, Apartment $apartment): void
    {
        if(isset($apartment->rentCustomer)){
            $phoneNumber = $apartment->rentCustomer->phone;
        } else {
            $phoneNumber = $apartment->customer->phone;
        }

        $phoneNumber = '964' . $phoneNumber .'@c.us';

        $this->waAPISendMessageAction->sendMessage($phoneNumber, $message);
    }
}
