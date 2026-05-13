<div class="col-span-1 sm:col-span-2 flex flex-col gap-2">
    <div class="px-2 py-3 bg-white dark:bg-slate-600 text-center primary-text-color text-lg font-bold leading-[28.80px]">
        {{ __('products') }}
    </div>
    <div class="flex relative gap-2">
        <input id="searchFeaturedProductInput" type="text"
            placeholder="{{ __('scan_search_featured_product_by_name_or_code') }}"
            class="form-input pl-11 px-4 py-3.5 bg-white dark:bg-slate-600 focus:ring-2 focus:ring-sky-500 outline-none border-none w-full" />
        <button class="bg-white dark:bg-slate-600 px-4">
            <img src="{{ asset('/icons/barcode.svg') }}" class="w-6 h-6" />
        </button>
        <div id="searchFeaturedProducts"
            class="absolute w-full p-3 shadow-lg border border-slate-200 bg-white flex flex-col gap-2 z-10 max-h-96 overflow-y-auto top-16">
        </div>
    </div>

    <!-- product list -->
    <div class="overflow-y-scroll customScroll md:max-h-[77vh]" id="productListSection">
        <div id="featuredProducts"
            class="grid grid-cols-1 gap-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        </div>
    </div>
</div>

