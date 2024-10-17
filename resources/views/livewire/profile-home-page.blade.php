<div>
    <h4 class="pb-3 border-b-2 text-amber-800"> مرحبا سيد <b> {{ $apartment->is_rent ? $apartment->rentCustomer->name : $apartment->customer->name  }} </b>  </h4>
    <div class="mt-5">
        <x-list-item :item="$apartment->tower" >
            <x-slot:avatar>
                <x-icon name="s-building-office-2" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ $apartment->tower->name }}
            </x-slot:value>
            <x-slot:sub-value>
                عمارة
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment->level" >
            <x-slot:avatar>
                <x-icon name="c-rectangle-group" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ $apartment->level->name }}
            </x-slot:value>
            <x-slot:sub-value>
                طابق
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="m-ellipsis-horizontal" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ $apartment->number }}
            </x-slot:value>
            <x-slot:sub-value>
                رقم شقة
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="s-viewfinder-circle" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ $apartment->view->toArabic() }}
            </x-slot:value>
            <x-slot:sub-value>
                الإطلالة
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="m-document-currency-dollar" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ number_format($apartment->balance) }} <b class="text-red-600"> د.ع   </b>
            </x-slot:value>
            <x-slot:sub-value>
                الرصيد دين
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="m-document-currency-dollar" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ number_format($apartment->balance_usd) }} <b class="text-red-600"> $   </b>
            </x-slot:value>
            <x-slot:sub-value>
                الرصيد دين دولار
            </x-slot:sub-value>
        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="s-shopping-cart" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ number_format($apartment->services()->count()) }} <b class="text-red-600"> مرات   </b>
            </x-slot:value>
            <x-slot:sub-value>
                خدمات
            </x-slot:sub-value>

            <x-slot:actions>
                <x-button icon="o-queue-list" class="text-yellow-500" spinner :link="route('profile.service', $apartment->uuid)" />
            </x-slot:actions>

        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-maki-drinking-water class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ number_format($apartment->waters()->count()) }} <b class="text-red-600"> مرات   </b>
            </x-slot:value>
            <x-slot:sub-value>
                ماء
            </x-slot:sub-value>

            <x-slot:actions>
                <x-button icon="o-queue-list" class="text-yellow-500" spinner :link="route('profile.water', $apartment->uuid)" />
            </x-slot:actions>

        </x-list-item>

        <x-list-item :item="$apartment" >
            <x-slot:avatar>
                <x-icon name="c-fire" class="w-12 h-12 bg-amber-800 text-white p-2 rounded-full" />
            </x-slot:avatar>
            <x-slot:value>
                {{ number_format($apartment->gas_unit) }} <b class="text-red-600"> مكعب   </b>
            </x-slot:value>
            <x-slot:sub-value>
                الغاز
            </x-slot:sub-value>

            <x-slot:actions>
                <x-button icon="o-queue-list" class="text-yellow-500" spinner :link="route('profile.gas', $apartment->uuid)" />
            </x-slot:actions>

        </x-list-item>
    </div>

</div>
