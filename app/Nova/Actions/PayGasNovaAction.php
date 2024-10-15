<?php

namespace App\Nova\Actions;

use App\Actions\PayGasAction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class PayGasNovaAction extends Action
{
    use InteractsWithQueue, Queueable;


    public function handle(ActionFields $fields, Collection $models): ActionResponse|Action
    {
        if($fields->is_paid !== true) {
            return Action::danger('Please check the box to confirm payment.');
        }

        $action = resolve(PayGasAction::class);
        foreach ($models as $model) {
            try {
                if($model->is_paid) {
                    return Action::danger('Gas already paid.');
                }
                $action->execute($model);
            } catch (Exception $e) {
                return Action::danger($e->getMessage());
            }
        }

        return Action::redirect(route('print.gas', $models->first()->id));
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
