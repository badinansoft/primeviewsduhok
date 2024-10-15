<?php

namespace App\Nova\Actions;

use App\Data\ServiceDataBulkCreating;
use App\Jobs\CreateBulkServiceInBackgroundJob;
use App\Models\Tower;
use App\Settings\Settings;
use Carbon\Carbon;
use Datomatic\Nova\Tools\DetachedActions\DetachedAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ServiceCreateAction extends DetachedAction
{
    use InteractsWithQueue, Queueable;

    public $onlyOnIndex = true;

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $data = new ServiceDataBulkCreating(
            $fields->tower_id,
            $fields->type,
            Carbon::parse($fields->start_date),
            Carbon::parse($fields->end_date),
            $fields->amount,
            auth()->id(),
            $fields->notes,
        );

        dispatch(new CreateBulkServiceInBackgroundJob($data));

        return DetachedAction::message('Service creation has been started in the background');
    }

    public function fields(NovaRequest $request): array
    {
        $towers = Tower::query()->pluck('name', 'id')->all();
        $settings = app(Settings::class);
        return [

            Select::make(__('Tower'), 'tower_id')
                ->options($towers)
                ->searchable()
                ->rules('required'),

            BooleanGroup::make(__('Type'), 'type')->options([
                'type1' => 'Level 0',
                'type2' => 'Normal',
                'type3' => 'Top Level',
            ]),

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
        return __('Create Bulk Service');
    }
}
