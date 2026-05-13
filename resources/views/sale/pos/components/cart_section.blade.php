<div class="lg:max-h-[64vh] lg:min-h-[300px] mt-2 overflow-y-scroll overflow-x-auto customScroll" id="procusctTable">
    <!-- product table -->
    <table class="table-auto w-full">
        <thead class="primary-bg-color-light dark:bg-slate-600 sticky top-0">
            <tr>
                <th class="p-2 text-left font-bold pl-4 primary-text-color text-base">
                    {{ __('product') }}
                </th>
                <th class="p-2 text-center primary-text-color text-base font-bold">
                    {{ __('imei_variant') }}
                </th>
                <th class="p-2 text-center primary-text-color text-base font-bold">
                    {{ __('tax') }}
                </th>
                <th class="p-2 text-center primary-text-color text-base font-bold">
                    {{ __('price') }}
                </th>
                <th class="p-2 text-center primary-text-color text-base font-bold">
                    {{ __('qty') }}
                </th>
                <th class="p-2 w-24 text-right primary-text-color text-base font-bold">
                    {{ __('subtotal') }}
                </th>
                <th class="w-12"></th>
            </tr>
        </thead>
        <tbody id="selectProducts">
            @if ($sales)
                @foreach ($sales->productSales as $productSales)
                    @php
                        $tax = $productSales->product->tax->rate ?? 0;
                        if ($tax > 0) {
                            $tax = ($productSales->net_unit_price * $productSales->product->tax->rate) / 100;
                        }
                    @endphp
                    <tr class="productSaleRow" id="productSaleRow_{{ $productSales->product->id }}"
                        data-id="{{ $productSales->product->id }}">
                        <td class="p-2 h-12 text-cyan-900 text-base font-normal border-b primary-border-color">
                            <a href="javascript:void(0)"
                                class="truncate w-44 clip text-ellipsis productPriceCustomizeModal"
                                data-id="{{ $productSales->product->id }}">
                                {{ $productSales->product->name }}
                            </a>
                            <!-- Product price customization Modal Start -->
                            <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true"
                                id="productPriceCustomizationModal_{{ $productSales->product->id }}">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">​</span>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                        id="modal-title">
                                                        {{ __('product_price_customization') }}
                                                    </h3>
                                                    <div class="mt-2">
                                                        <div class="grid gap-4 md:grid-cols-2 mt-2 w-full">
                                                            <div class="mt-3">
                                                                <label class="text-slate-500"
                                                                    for="name">{{ __('product_name') }}
                                                                </label>
                                                                <input type="text"
                                                                    value="{{ $productSales->product->name }}" disabled
                                                                    id="product_name_{{ $productSales->product->id }}"
                                                                    placeholder="{{ __('enter_your_product_name') }}"
                                                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2" />
                                                            </div>
                                                            <div class="mt-3">
                                                                <label class="text-slate-500"
                                                                    for="product_price">{{ __('product_price') }}
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text"
                                                                    value="{{ $productSales->net_unit_price }}"
                                                                    id="product_price_{{ $productSales->product->id }}"
                                                                    data-tax-rate="{{ $productSales->product->tax->rate ?? 0 }}"
                                                                    name="price"
                                                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                                                    placeholder="{{ __('enter_your_product_price') }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm submitProductPriceCustomizationModal"
                                                data-id="{{ $productSales->product->id }}">
                                                {{ __('update_and_save') }}
                                            </button>
                                            <button type="button"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm closeProductPriceCustomizationModal"
                                                data-id="{{ $productSales->product->id }}">
                                                {{ __('cancel') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Product price customization Modal End -->
                        </td>
                        <td
                            class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color">
                            {{ numberFormat($productSales->product->discount ?? 0) }}
                        </td>
                        <td class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color"
                            id="productTax_{{ $productSales->product->id }}">
                            {{ numberFormat($tax) }}
                        </td>
                        <td class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color productPrice"
                            data-price="{{ $productSales->net_unit_price }}"
                            id="productPrice_{{ $productSales->product->id }}">
                            {{ numberFormat($productSales->net_unit_price) }}
                        </td>
                        <td
                            class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color">
                            <div class="flex justify-center items-center gap-2">
                                <button class="w-4 h-4 primary-bg-color rounded-sm flex justify-center items-center"
                                    onclick="minusProduct({{ $productSales->product->id }})">
                                    <img src="{{ asset('/icons/minus.svg') }}" class="w-4 h-4" />
                                </button>
                                <span class="text-cyan-900 text-base font-normal tracking-tight productQty"
                                    id="productQty_{{ $productSales->product->id }}">
                                    {{ $productSales->qty }}
                                </span>
                                <button class="w-4 h-4 primary-bg-color rounded-sm flex justify-center items-center"
                                    onclick="addProduct({{ $productSales->product->id }}, {{ $productSales->product->qty }})">
                                    <img src="{{ asset('/icons/plus.png') }}" class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                        <td class="p-2 h-12 w-24 text-right text-cyan-900 text-base font-normal border-b primary-border-color productSubtotal"
                            id="productSubtotal_{{ $productSales->product->id }}"
                            data-subtotal="{{ $productSales->net_unit_price + $tax }}">
                            {{ numberFormat(($productSales->net_unit_price + $tax) * $productSales->qty) }}
                        </td>
                        <td class="w-12 h-12 border-b primary-border-color text-center">
                            <div class="p-0 m-auto flex w-5 h-5 bg-red-400 rounded-full justify-center items-center cursor-pointer"
                                onclick="removeProductFromCart({{ $productSales->product->id }})">
                                <img src="{{ asset('/icons/remove.svg') }}" class="w-2 h-2" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif

            <tr id="noProducts" @if ($sales) style="display: none;" @endif>
                <td colspan="7" class="h-12 border-b primary-border-color text-center">
                    {{ __('no_products_available_in_the_list') }}
                </td>
            </tr>
        </tbody>
        <tfoot class="sticky bottom-0 bg-white ">
            <tr>
                <td colspan="5"
                    class="p-2 h-12 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light text-cyan-900 dark:text-slate-100 text-base font-medium">
                    {{ __('total_products') }}:
                </td>
                <td
                    class="text-right p-2 h-12 w-24 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light text-cyan-900 dark:text-slate-100 text-base font-medium">
                    <span id="totalProduct">{{ $sales->item ?? 0 }}</span>(<span
                        id="totalItem">{{ $sales->total_qty ?? 0 }}</span>)
                </td>
                <td class="p-2 w-12 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600">
                </td>
            </tr>

            <tr>
                <td colspan="5"
                    class="p-2 h-12 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light text-cyan-900 dark:text-slate-100 text-base font-medium">
                    {{ __('total_amount') }}:
                </td>
                <td
                    class="text-right p-2 h-12 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light text-cyan-900 dark:text-slate-100 text-base font-medium">
                    <span id="totalAmount">{{ numberFormat($sales->total_price ?? 0) }}</span>
                </td>
                <td class="p-2 w-12 h-12 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light">
                </td>
            </tr>

            <tr>
                <td colspan="5"
                    class="p-2 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600 text-cyan-900 dark:text-slate-100 text-base font-medium">
                    {{ __('discount') }}
                </td>
                <td
                    class="text-right p-2 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600 text-rose-400 text-base font-medium">
                    -
                    <span id="totalDiscount">{{ numberFormat($sales->order_discount ?? 0) }}</span>
                </td>
                <td class="p-2 w-12 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600">
                </td>
            </tr>

            <tr>
                <td class="p-2 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600 text-cyan-900 dark:text-slate-100 text-base font-medium"
                    colspan="5">
                    <div class="flex gap-2">
                        <span>{{ __('coupon') }}</span>

                        <div class="relative">
                            <input type="text"
                                class="w-[198.88px] h-8 pl-2 pr-1 py-1 primary-bg-color-light dark:bg-slate-600 rounded-[5px] text-gray-500 text-base font-normal border-slate-300 outline-none"
                                placeholder="Coupon code.." id="couponCodeInput" />
                            <button
                                class="absolute right-1 top-2/4 -translate-y-2/4 bg-green-500 rounded-[3px] w-6 h-6 flex justify-center items-center"
                                id="applyCouponBtn">
                                <img src="{{ asset('/icons/checked.svg') }}" class="w-6 h-6">
                            </button>
                            <button
                                class="absolute right-1 top-2/4 -translate-y-2/4 bg-red-100 w-6 h-6 flex justify-center items-center"
                                id="removeCouponBtn">
                                <img src="{{ asset('/icons/removed.svg') }}" class="w-4 h-4 p-[2px]">
                            </button>
                        </div>

                        <button class="w-8 h-8 rounded-md bg-blue-50 cursor-pointer flex justify-center items-center"
                            id="addCouponBtn">
                            <img src="{{ asset('/icons/plus.png') }}" class="w-6 h-6" />
                        </button>
                    </div>
                </td>
                <td class="p-2 h-12 primary-bg-color-light border-b primary-border-color-light dark:bg-slate-600 text-cyan-900 dark:text-slate-100 text-base font-medium text-right discount"
                    data-coupon-id="">{{ numberFormat($sales->coupon_discount ?? 0) }}</td>
                <td class="p-2 w-12 h-12 primary-bg-color-light dark:bg-slate-600 border-b primary-border-color-light">
                </td>
            </tr>
            <tr>
                <td class="primary-bg-color p-2 pr-0" colspan="5">
                    <div class="w-full justify-end inline-flex text-blue-50 text-base font-bold">
                        <span>{{ __('grand_total') }}:</span>
                    </div>
                </td>
                <td class="primary-bg-color p-2 text-right">
                    <div class="text-blue-50 text-base font-bold"><span id="totalGrand"
                            data-grand-price="{{ $sales->grand_total ?? 0 }}">{{ numberFormat($sales->grand_total ?? 0) }}</span>
                    </div>
                </td>
                <td class="primary-bg-color p-2 w-12 h-12"></td>
            </tr>
        </tfoot>
    </table>
</div>
