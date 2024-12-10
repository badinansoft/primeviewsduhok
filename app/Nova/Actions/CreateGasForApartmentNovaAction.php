<?php

namespace App\Nova\Actions;

use App\Jobs\SendInvoiceOverWhatsAppJob;
use App\Models\Apartment;
use App\Models\Gas;
use App\Models\Service;
use App\Settings\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lednerb\ActionButtonSelector\ShowAsButton;

class CreateGasForApartmentNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use ShowAsButton;

    private ?Apartment $apartment;

    public function __construct(Service|Apartment $model)
    {
        if($model instanceof Service && $model->apartment instanceof Apartment) {
            $this->apartment = $model->apartment;
        } elseif($model instanceof Apartment) {
            $this->apartment = $model;
        }else {
            $this->apartment = null;
        }
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $gas = new Gas();
        $gas->date = $fields->date;
        $gas->apartment_id = $models->first()->id;
        $gas->unit_price = $fields->amount;
        $gas->current_unit = $fields->current_unit;
        $gas->notes = $fields->notes;
        $gas->discount = $fields->discount;
        $gas->attachment = $fields->attachment instanceof UploadedFile ?  $this->storeImage($fields->attachment, $fields->date): '';

        if($fields->is_paid) {
            $gas->paid_at = now();
            $gas->paid_by = auth()->id();
        }

        $gas->save();

        dispatch(new SendInvoiceOverWhatsAppJob($gas));


        if($fields->is_paid) {
            return Action::redirect(route('print.gas', $gas->id));
        }

        return Action::message('Gas record created successfully');
    }

    private function storeImage(UploadedFile $file, string $date): string
    {
        $file->storePublicly('/public/gas/' . auth()->id() . '/' . $date);
        return 'gas/' . auth()->id() . '/' . $date . '/' . $file->hashName();
    }


    public function fields(NovaRequest $request): array
    {
        $settings = app(Settings::class);
        return [
            Heading::make('<p class="text-danger"> In here you will create new Gas record for apartment.</p>')->asHtml(),

            Date::make(__('Date'), 'date')
                ->rules('required'),

            Currency::make(__('Gas Unit Amount'), 'amount')
                ->rules('required', 'numeric')
                ->default($settings->get('default_gas_unit_amount', 0)),

            Number::make(__('Last Apartment Gas Unit'), 'last_unit')
                ->default($this->apartment?->gas_unit)
                ->rules('required', 'numeric')
                ->readonly(),

            Number::make(__('Current Unit'), 'current_unit')
                ->rules('required', 'numeric', 'gt:last_unit')
                ->step(0.01)
                ->help('This must be greater than last unit. that mean to be greater than ' . $this->apartment?->gas_unit),

            Number::make(__('Consumption'), 'consumption')
                ->rules('required', 'numeric', 'gt:0')
                ->step(0.01)
                ->help('This must be greater than 0')
                ->readonly()
                ->dependsOn(['last_unit', 'current_unit'], function (Number $field, NovaRequest $request, FormData $formData) {
                    $lastUnit = $formData->get('last_unit', 0.0);
                    $currentUnit = $formData->get('current_unit', 0.0);
                    $field->value = number_format($currentUnit - $lastUnit, 2, '.', '');
                }),


            Text::make(__('Total Amount'), 'total_amount')
                ->rules('required', 'numeric', 'gt:0')
                ->help('This must be greater than 0')
                ->readonly()
                ->dependsOn(['amount', 'consumption'], function (Text $field, NovaRequest $request, FormData $formData) {
                    $amount = $formData->get('amount', 0.0);
                    $consumption = $formData->get('consumption', 0.0);
                    $field->value = 'IQD ' . number_format($amount * $consumption, 0, '.', ',');
                }),

            Number::make(__('Discount'), 'discount')
                ->default(0)
                ->rules('required', 'numeric', 'gt:-1')
                ->step(0.01)
                ->help('This must be greater than -1'),

            Text::make(__('Total Amount After Discount'), 'total_amount_after_discount')
                ->rules('required', 'numeric', 'gt:0')
                ->help('This must be greater than 0')
                ->readonly()
                ->dependsOn(['amount', 'consumption', 'discount'], function (Text $field, NovaRequest $request, FormData $formData) {
                    $amount = $formData->get('amount', 0.0);
                    $consumption = $formData->get('consumption', 0.0);
                    $discount = $formData->get('discount', 0.0);
                    $field->value = 'IQD ' . number_format(($amount * $consumption) - $discount, 0, '.', ',');
                }),

            File::make(__('Attachment'), 'attachment')
                ->disk('public')
                ->storeAs(function (NovaRequest $request) {
                    return 'gas/' . $request->user()->id . '/' . $request->date . '/';
                })
                ->rules('nullable', 'file', 'mimes:pdf,doc,docx,txt,png,jpg,jpeg')
                ->help('This must be a file (pdf, doc, docx, txt, png, jpg, jpeg)'),


            Boolean::make(__('Check Here for Payment'), 'is_paid')
                ->help(__('Check this box to confirm payment.')),

            Textarea::make(__('Notes'), 'notes'),
        ];
    }

    public function name(): string
    {
        return 'Create Gas Service';
    }
}
