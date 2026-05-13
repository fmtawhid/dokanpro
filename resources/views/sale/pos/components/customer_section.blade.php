<div class="relative">
    <div class="flex gap-2 mb-2">
        <div class="flex grow relative">
            <img src="{{ asset('/icons/user-tie-solid.svg') }}"
                class="w-6 h-6 absolute top-2/4 transform -translate-y-2/4 left-3" />
            <input id="searchCustomerInput" type="text" placeholder="{{ __('enter_customer_name_or_phone_number') }}"
                class="form-input pl-11 px-4 py-3.5 bg-white dark:bg-slate-600 focus:ring-2 focus:ring-sky-500 outline-none border-none w-full"
                data-id="" />
        </div>
        <button class="h-13 bg-white dark:bg-slate-600 px-4" id="addCustomerBtn">
        <img src="{{ asset('/icons/plus.png') }}" class="w-6 h-6"/>
        </button>
    </div>
    <div id="searchCustomers"
        class="absolute w-full p-3 shadow-lg border border-slate-200 bg-white flex flex-col gap-2 z-10 max-h-96 overflow-y-auto">
    </div>
</div>
