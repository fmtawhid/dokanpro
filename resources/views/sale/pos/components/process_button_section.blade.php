<div class="flex flex-wrap gap-2 mt-4">
    <button class="h-12 px-4 text-base font-medium leading-tight tracking-tight grow text-red-600 bg-rose-100"
        onclick="cancelSale()">
        {{ __('cancel') }}
    </button>
    <button class="h-12 px-4 text-base font-medium leading-tight tracking-tight grow text-blue-50 bg-amber-300"
        onclick="complate('Draft')">
        {{ __('draft') }}
    </button>
    <button class="h-12 px-4 text-base font-medium leading-tight tracking-tight grow text-blue-50 primary-bg-color save_and_complate"
        onclick="complate('Sales')" disabled>
        {{ __('save_and_complate') }}
    </button>
</div>
<div class="flex flex-wrap gap-2 mt-4">
    <div id="paypal-button-container" class="h-12 text-base font-medium leading-tight tracking-tight grow text-blue-50 "></div>
</div>


