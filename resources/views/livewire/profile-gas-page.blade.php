<div>
    <h2 class="text-2xl font-bold">
        <x-button icon="o-arrow-right" class="btn-circle btn-outline ml-5" :link="route('profile.show', $apartment->uuid)" />

        <b> الغاز</b>
    </h2>
    <h4 class="pb-3 border-b-2"> مرحبا سيد <b> {{ $apartment->is_rent ? $apartment->rentCustomer->name : $apartment->customer->name  }} </b>  </h4>
    <div class="mt-5">

        @foreach($apartment->gas->sortBy('date')->all() as $gas)
            <x-list-item :item="$gas" >
                <x-slot:avatar>
                    @if($gas->is_paid)
                        <x-icon name="s-check-badge" class="w-12 h-12 bg-green-800 text-white p-2 rounded-full" />
                    @else
                        <x-icon name="c-x-circle" class="w-12 h-12 bg-red-500 text-white p-2 rounded-full" />
                    @endif
                </x-slot:avatar>
                <x-slot:value>
                    {{ number_format($gas->total_price) }} <b class="text-red-600"> د.ع   </b> - {{ $gas->consumption }} <b class="text-red-600"> مكعب   </b>
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $gas->date->locale('ar')->diffForHumans() }}
                    @if($gas->is_paid)
                        -      <span class="text-green-600"> <b>تم الدفع</b>  {{ $gas->paid_at->locale('ar')->diffForHumans() }}</span>
                    @endif
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="s-document-currency-dollar" external class="text-blue-500" spinner :link="route('profile.invoice.gas', ['uuid'=>$apartment->uuid, 'id'=>$gas->id])" />
                </x-slot:actions>
            </x-list-item>
        @endforeach

    </div>
</div>
