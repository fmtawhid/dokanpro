<div class="col-span-1 bg-white p-2 dark:bg-slate-500" id="categoryBrandSection">
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-2">
        <div class="px-2 py-3 truncate bg-white dark:bg-slate-600 text-center primary-text-color text-lg font-bold leading-[28.80px] mb-2 cursor-pointer category-brand border primary-border-color rounded customActive"
            id="categoryBtn">
            {{ __('categories') }}
        </div>

        <div class="px-2 py-3 bg-white dark:bg-slate-600 text-center primary-text-color text-lg font-bold leading-[28.80px] mb-2 cursor-pointer category-brand border primary-border-color rounded"
            id="brandBtn">
            {{ __('brands') }}
        </div>
    </div>
    <div class="md:max-h-[83vh] overflow-y-scroll customScroll">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-2" id="categories"></div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-2" id="brands"></div>
    </div>
</div>
