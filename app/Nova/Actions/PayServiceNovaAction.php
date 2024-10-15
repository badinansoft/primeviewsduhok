<?php

namespace App\Nova\Actions;

use App\Actions\PayServiceAction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class PayServiceNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models): ActionResponse|Action
    {
        if($fields->is_paid !== true) {
            return Action::danger('Please check the box to confirm payment.');
        }

        $action = resolve(PayServiceAction::class);
        foreach ($models as $model) {
            try {
                if($model->is_paid) {
                    return Action::danger('Service already paid.');
                }
                $action->execute($model);
            } catch (Exception $e) {
                return Action::danger($e->getMessage());
            }
        }

        return Action::redirect(route('print.service', $models->first()->id));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make(__('Check Here for Payment'), 'is_paid')
                ->help(__('Check this box to confirm payment.'))
        ];
    }

    public function name(): string
    {
        return __('Pay');
    }

}
