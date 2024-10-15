<?php

namespace App\Nova\Actions;

use App\Actions\DeleteBulkServiceAction;
use App\Models\Tower;
use Carbon\Carbon;
use Datomatic\Nova\Tools\DetachedActions\DetachedAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeleteBulkServiceNovaAction extends DetachedAction
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $action = new DeleteBulkServiceAction();

        $result = $action->run(
            $fields->tower_id,
            Carbon::parse($fields->start_date),
            Carbon::parse($fields->end_date)
        );

        return Action::message("Deleted {$result} services");
    }

    public function fields(NovaRequest $request): array
    {
        $towers = Tower::query()->pluck('name', 'id')->all();
        return [
            Select::make(__('Tower'), 'tower_id')
                ->options($towers)
                ->searchable()
                ->rules('required'),

            Date::make(__('Start Date'), 'start_date')
                ->rules('required'),

            Date::make(__('End Date'), 'end_date')
                ->rules('required', 'after:start_date'),
        ];
    }
}
