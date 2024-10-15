<div>
    <h2 class="text-2xl font-bold">
        <x-button icon="o-arrow-right" class="btn-circle btn-outline ml-5" :link="route('profile.show', $apartment->uuid)" />

        <b>خدمات</b>
    </h2>
    <h4 class="pb-3 border-b-2"> مرحبا سيد <b> {{ $apartment->is_rent ? $apartment->rentCustomer->name : $apartment->customer->name  }} </b>  </h4>
    <div class="mt-5">

        @foreach($apartment->services->sortBy('date')->all() as $service)
            <x-list-item :item="$service" >
                <x-slot:avatar>
                    @if($service->is_paid)
                        <x-icon name="s-check-badge" class="w-12 h-12 bg-green-800 text-white p-2 rounded-full" />
                    @else
                        <x-icon name="c-x-circle" class="w-12 h-12 bg-red-500 text-white p-2 rounded-full" />
                    @endif
                </x-slot:avatar>
                <x-slot:value>
                    {{ number_format($service->amount) }} <b class="text-red-600"> د.ع   </b>
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $service->end_date->locale('ar')->diffForHumans() }}

                    @if($service->is_paid)
                        -   <span class="text-green-600"> <b>تم الدفع</b>  {{ $service->paid_at->locale('ar')->diffForHumans() }}</span>
                    @endif
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="s-document-currency-dollar" external class="text-blue-500" spinner :link="route('profile.invoice.service', ['uuid'=>$apartment->uuid, 'id'=>$service->id])" />
                </x-slot:actions>
            </x-list-item>
        @endforeach

    </div>
</div>
