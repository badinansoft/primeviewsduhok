<div>
    <h2 class="text-2xl font-bold">
        <x-button icon="o-arrow-right" class="btn-circle btn-outline ml-5 bg-amber-800 text-white" :link="route('profile.show', $apartment->uuid)" />

        <b class="text-amber-800">ماء</b>
    </h2>
    <h4 class="pb-3 border-b-2 text-amber-800"> مرحبا سيد <b> {{ $apartment->is_rent ? $apartment->rentCustomer->name : $apartment->customer->name  }} </b>  </h4>
    <div class="mt-5">

        @foreach($apartment->waters->sortBy('date')->all() as $water)
            <x-list-item :item="$water" >
                <x-slot:avatar>
                    @if($water->is_paid)
                        <x-icon name="s-check-badge" class="w-12 h-12 bg-green-800 text-white p-2 rounded-full" />
                    @else
                        <x-icon name="c-x-circle" class="w-12 h-12 bg-red-500 text-white p-2 rounded-full" />
                    @endif
                </x-slot:avatar>
                <x-slot:value>
                    {{ number_format($water->amount) }} <b class="text-red-600"> د.ع  </b>
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $water->end_date->locale('ar')->diffForHumans() }}

                    @if($water->is_paid)
                        -   <span class="text-green-600"> <b>تم الدفع</b>  {{ $water->paid_at->locale('ar')->diffForHumans() }}</span>
                    @endif
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="s-document-currency-dollar" external class="text-amber-700" spinner :link="route('profile.invoice.water', ['uuid' => $apartment->uuid, 'id'=> $water->id])" />
                </x-slot:actions>
            </x-list-item>
        @endforeach

    </div>
</div>
