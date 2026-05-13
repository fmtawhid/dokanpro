<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="{{ feature('barcodes') ? 'col-md-6' : 'col-md-12' }} mb-3">
                        <x-inputGroup name="name" title="{{ __('name') }}" :required="true" type="text"
                            value="{{ isset($product) ? $product->name : old('name') }}"
                            placeholder="{{ __('enter_your_product_name') }}" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select name="type" title="{{ __('type') }}" :required="true"
                            placeholder="{{ __('select_a_option') }}">
                            @foreach ($productTypes as $type)
                                <option
                                    {{ (isset($product) && $product->type->value == $type->value) || old('type') == $type->value ? 'selected' : '' }}
                                    value="{{ $type->value }}">{{ $type->value }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    @if (feature('barcodes'))
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <x-inputGroup type="number" name="code" :required="true"
                                        title="{{ __('code') }}"
                                        placeholder="{{ __('enter_your_code_or_generate') }}"
                                        value="{{ isset($product) ? $product->code : old('code') }}"
                                        :required="true"></x-inputGroup>
                                </div>
                                <div class="col-md-1" style="margin-top: 30px">
                                    <button type="button" class="btn common-btn" id="generate-code"><i
                                            class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <div class="col-md-6 mb-3">
                        <x-select name="barcode_symbology" title="{{ __('barcode_symbology') }}"
                            placeholder="{{ __('select_a_option') }}" :required="true">
                            @foreach ($barcodeSymbologyes as $barcodeSymbology)
                                <option
                                    {{ (isset($product) && $product->barcode_symbology->value == $barcodeSymbology->value) || old('barcode_symbology') == $barcodeSymbology->value ? 'selected' : '' }}
                                    value="{{ $barcodeSymbology->value }}">
                                    {{ $barcodeSymbology->value }}
                                </option>
                            @endforeach
                        </x-select>
                    </div> --}}
                    <div id="{{ isset($product) && $product->type->value == 'Combo' ? '' : 'combo-section' }}"
                        class="col-12 mb-3">
                        <div class="combo-filed">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="mb-2">{{ __('add_product') }}</label>
                                    <div class="search-box input-group mb-3">
                                        <button class="btn common-btn"><i class="fa fa-barcode"></i></button>
                                        <input type="text" name="product_code_name" id="searchProduct"
                                            placeholder="{{ __('please_type_product_code_and_select') }}"
                                            class="form-control" />
                                        <div class="position-absolute w-100 products p-2 shadow" id="productList">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-hover order-list">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('product') }}</th>
                                                    <th>{{ __('code') }}</th>
                                                    <th>{{ __('quantity') }}</th>
                                                    <th>{{ __('purchase_cost') }}</th>
                                                    <th>{{ __('sub_total') }}</th>
                                                    <th>{{ __('action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="comboProduct">
                                                @if (isset($compoProducts) && count($compoProducts) > 0)
                                                    @foreach ($compoProducts as $compoProduct)
                                                        @php
                                                            $tax = $product->tax->rate ?? 0;
                                                            if ($tax > 0) {
                                                                $tax =
                                                                    ($compoProduct['price'] * $product->tax->rate) /
                                                                    100;
                                                            }
                                                            $subTotal = $compoProduct['price'] + $tax;
                                                        @endphp
                                                        <tr class="productPurchaseRow"
                                                            id="productPurchaseRow_${product.id}"
                                                            data-id="{{ $compoProduct['product']->id }}"
                                                            data-total="0">
                                                            <input type="hidden" name="product_id[]"
                                                                value="{{ $compoProduct['product']->id }}">
                                                            <td>{{ $compoProduct['product']->name }}</td>
                                                            <td>{{ $compoProduct['product']->code }}</td>
                                                            <td>
                                                                <input type="number" class="form-control qty"
                                                                    name="qty[]" id="" onchange="countQty()"
                                                                    value="{{ $compoProduct['qty'] }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control net_unit_cost"
                                                                    onchange="countQty()" name="netUnitCost[]"
                                                                    value="{{ $compoProduct['price'] }}">
                                                            </td>
                                                            <td class="sub-total">{{ $subTotal }}</td>
                                                            <td>
                                                                <a
                                                                    onclick="deleteRow({{ $compoProduct['product']->id }})"><i
                                                                        class="fa fa-times text-danger"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select name="brand_id" title="{{ __('brand') }}" :required="true"
                            placeholder="{{ __('select_a_option') }}">
                            @foreach ($brands as $brand)
                                <option
                                    {{ (isset($product) && $product->brand_id == $brand->id) || old('brand_id') == $brand->id ? 'selected' : '' }}
                                    value="{{ $brand->id }}">{{ $brand->title }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select name="category_id" title="{{ __('category') }}"
                            placeholder="{{ __('select_a_option') }}" :required="true">
                            @foreach ($categories as $category)
                                <option
                                    {{ (isset($product) && $product->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="row" id="unit-section">
                        <div class="col-md-4 mb-3">
                            <x-select name="unit_id" title="{{ __('product_unit') }}"
                                placeholder="{{ __('select_a_option') }}">
                                @foreach ($units as $unit)
                                    <option
                                        {{ (isset($product) && $product->unit_id == $unit->id) || old('unit_id') == $unit->id ? 'selected' : '' }}
                                        value="{{ $unit->id }}">
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select name="sale_unit_id" title="{{ __('sale_unit') }}" :required="false"
                                placeholder="{{ __('select_a_sale_unit') }}">
                                @if (isset($product->unit->id) && $product->unit->id)
                                    <option selected value="{{ $product->unit->id }}">
                                        {{ $product->unit->name }}</option>
                                @endif
                            </x-select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select name="purchase_unit_id" title="{{ __('purchase_unit') }}" :required="false"
                                placeholder="{{ __('select_a_purchase_unit') }}">
                                @if (isset($product->unit->id) && $product->unit->id)
                                    <option selected value="{{ $product->unit->id }}">
                                        {{ $product->unit->name }}</option>
                                @endif
                            </x-select>
                        </div>
                    </div>
                    <div class="{{ feature('purchases') ? 'col-md-4' : 'col-md-6' }}  mb-3">
                        <x-input name="cost" title="{{ __('purchase_cost') }}" type="text"
                            placeholder="{{ __('enter_your_purchase_cost') }}" :required="true"
                            value="{{ isset($product) ? $product->cost : old('cost') }}" />
                    </div>
                    <div class="{{ feature('purchases') ? 'col-md-4' : 'col-md-6' }} mb-3">
                        <x-input name="price" title="{{ __('selling_price') }}" type="text"
                            placeholder="{{ __('enter_your_selling_price') }}"
                            value="{{ (isset($product) ? $product->price : old('price')) ?? 0}}" />
                    </div>
                    @if (feature('purchases'))
                        <div class="col-md-4 mb-3">
                            <x-input name="alert_quantity" title="{{ __('alert_quantity') }}" type="number"
                                placeholder="{{ __('enter_your_alert_quantity') }}" :required="false"
                                value="{{ isset($product) ? $product->alert_quantity : old('alert_quantity') }}" />
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('product_details') }}</label>
                            <textarea name="product_details" id="summernote">{!! isset($product) ? $product->product_details : old('product_details') !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12" style="text-align: right">
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-input type="file" title="{{ __('image') }}" name="image" :required="false"
                            placeholder="" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-select name="tax_id" title="{{ __('tax') }}" :required="false"
                            placeholder="{{ __('select_a_option') }}">
                            @foreach ($taxs as $tax)
                                <option
                                    {{ (isset($product) && $product->tax_id == $tax->id) || old('tax_id') == $tax->id ? 'selected' : '' }}
                                    value="{{ $tax->id }}">
                                    {{ $tax->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-select name="tax_method" title="{{ __('tax_method') }}" :required="false"
                            placeholder="{{ __('select_a_option') }}">
                            @foreach ($taxMethods as $taxMethod)
                                <option
                                    {{ isset($product->tax_method->value) && $taxMethod->value == $product->tax_method->value ? 'selected' : '' }}
                                    value="{{ $taxMethod->value }}">{{ $taxMethod->value }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-check">
                            <input name="featured" {{ isset($product) && $product->is_featured ? 'checked' : '' }}
                                class="form-check-input" type="checkbox" value="1" id="isFeatured">
                            <label class="form-check-label" for="isFeatured">
                                {{ __('featured_product_will_be_displayed_in_pos') }}
                            </label>
                        </div>
                    </div>
                    @if (feature('product_serial_numbers') && feature('purchases'))
                        <div class="col-md-12 mb-2"
                            style="display: {{ isset($product) && $product->is_batch ? 'none' : 'block' }}"
                            id="serial_imei_number_section">
                            <div class="form-check">
                                <input name="serial_imei_number"
                                    {{ isset($product) && $product->serial_imei_number ? 'checked' : '' }}
                                    class="form-check-input" type="checkbox" value="1" id="serial_imei_number">
                                <label class="form-check-label" for="serial_imei_number">
                                    {{ __('this_product_has_serial_or_imei_number') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    @if (feature('purchases') && feature('product_batch_and_expire_date'))
                        <div class="col-md-12 mb-2"
                            style="display: {{ isset($product) && $product->is_batch ? 'none' : 'block' }}"
                            id="batch-option">
                            <div class="form-check">
                                <input {{ isset($product) && $product->is_batch ? 'checked' : '' }} name="is_batch"
                                    type="checkbox" id="is-batch" value="1" class="form-check-input">
                                <label class="form-check-label"
                                    for="is-batch">{{ __('this_product_has_batch_and_expired_date') }}</label>
                            </div>
                        </div>
                    @endif
                    @if (feature('product_variants'))
                        <div class="col-md-12 mb-2" id="variant-option">
                            <div class="form-check">
                                <input {{ isset($product) && $product->is_variant == 1 ? 'checked' : '' }}
                                    name="is_variant" type="checkbox" id="is-variant" value="1"
                                    class="form-check-input">
                                <label class="form-check-label"
                                    for="is-variant">{{ __('this_product_has_variant') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12" id="variant-section">
                            <div class="col-md-12 form-group mt-2">
                                <input type="text" name="variant" class="form-control"
                                    placeholder="{{ __('enter_variant_seperated_by_comma') }}">
                            </div>
                            <div class="table-responsive ml-2">
                                <table id="variant-table" class="table table-hover variant-list">
                                    <thead>
                                        <tr>
                                            <th><i class="fa fa-circle"></i></th>
                                            <th>{{ __('name') }}</th>
                                            @if (feature('barcodes'))
                                                <th>{{ __('code') }}</th>
                                            @endif
                                            <th>{{ __('additional_price') }}</th>
                                            <th><i class="fa fa-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($product) && $product->productVariants->count() > 0)
                                            @foreach ($product->productVariants as $productVariant)
                                                <tr>
                                                    <td style="cursor:grab"><i class="fa fa-circle"></i>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="variant_name[]"
                                                            value="{{ $productVariant->variant->name }}">
                                                        <input type="hidden" class="form-control"
                                                            name="variant_id[]"
                                                            value="{{ $productVariant->variant->id }}">
                                                    </td>
                                                    @if (feature('barcodes'))
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="item_code[]"
                                                                value="{{ $productVariant->item_code }}">
                                                        </td>
                                                    @else
                                                        <input type="hidden" class="form-control" name="item_code[]"
                                                            value="{{ $productVariant->item_code }}">
                                                    @endif
                                                    <td>
                                                        <input type="number" class="form-control"
                                                            name="additional_price[]"
                                                            value="{{ $productVariant->additional_price }}"
                                                            step="any">
                                                    </td>
                                                    <td><button type="button"
                                                            class="vbtnDel btn btn-sm btn-danger">X</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 mb-2">
                        <div class="form-check">
                            <input {{ isset($product) && $product->is_promotion_price == 1 ? 'checked' : '' }}
                                name="promotion" type="checkbox" id="promotion" value="1"
                                class="form-check-input">
                            <label class="form-check-label"
                                for="promotion">{{ __('add_promotional_price') }}</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="promotion_price">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="mb-2">{{ __('promotional_price') }}</label>
                                    <input
                                        value="{{ isset($product) && $product->promotion_price ? $product->promotion_price : old('promotion_price') }}"
                                        type="number" name="promotion_price" class="form-control" step="any"
                                        placeholder="{{ __('enter_your_promotion_price') }}" />
                                </div>
                                <div class="col-md-4" id="start_date">
                                    <div class="form-group">
                                        <label class="mb-2">{{ __('start_date') }}</label>
                                        <div class="input-group">
                                            <input type="date" name="starting_date" id="starting_date"
                                                class="form-control"
                                                value="{{ isset($product) && $product->starting_date ? $product->starting_date : old('starting_date') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" id="last_date">
                                    <div class="form-group">
                                        <label class="mb-2">{{ __('end_date') }}</label>
                                        <div class="input-group">
                                            <input type="date" name="last_date" id="ending_date"
                                                class="form-control"
                                                value="{{ isset($product) && $product->ending_date ? $product->ending_date : old('last_date') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
