<?php

namespace App\Nova\Actions;

use App\Actions\CreateSingleApartmentServiceAction;
use App\Data\ServiceDataBulkCreating;
use App\Settings\Settings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lednerb\ActionButtonSelector\ShowAsButton;

class CreateApartmentSingleServiceNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use ShowAsButton;

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $data = new ServiceDataBulkCreating(
            0,
            [],
            Carbon::parse($fields->start_date),
            Carbon::parse($fields->end_date),
            $fields->amount,
            auth()->id(),
            $fields->notes,
        );

        $action = new CreateSingleApartmentServiceAction();
        foreach ($models as $apartment)
        {
            $action->execute($apartment, $data);
        }

        return Action::message('Service has been created successfully');
    }

    public function fields(NovaRequest $request): array
    {
        $settings = app(Settings::class);
        return [
            Heading::make('<p class="text-danger"> This action don\'t check if created service in same period so will create force.</p>')->asHtml(),

            Date::make(__('Start Date'), 'start_date')
                ->rules('required'),

            Date::make(__('End Date'), 'end_date')
                ->rules('required', 'after:start_date'),

            Number::make(__('Amount'), 'amount')
                ->rules('required', 'numeric')
                ->default($settings->get('default_service_amount', 0)),

            Textarea::make(__('Notes'), 'notes'),
        ];
    }

    public function name(): string
    {
        return 'Create Service';
    }
}
