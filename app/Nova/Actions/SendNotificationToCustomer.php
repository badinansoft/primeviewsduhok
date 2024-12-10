<?php

namespace App\Nova\Actions;

use App\Jobs\SendNotificationToCustomerViaWhatsAppJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendNotificationToCustomer extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): void
    {
        $models->each(function ($model) use ($fields) {
            dispatch(new SendNotificationToCustomerViaWhatsAppJob($fields->message, $model));
        });
    }


    public function fields(NovaRequest $request): array
    {
        return [
            Textarea::make('message', 'message')
                ->rows(10)
                ->required(),
        ];
    }
}
