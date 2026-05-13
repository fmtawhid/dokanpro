@extends('layout.app')
@section('title', __('purchase_create'))
@section('content')
    <section class="forms">
        <form action="{{ route('purchase.store') }}" method="POST" id="purchase-form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header custom-card-header d-flex align-items-center card-header-color">
                            <span class="list-title text-white">{{ __('new_purchase') }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <x-select name="warehouse_id" title="warehouse" :required="true"
                                                placeholder="select_a_option">
                                                @foreach ($warehouses as $warehouse)
                                                    <option {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                                        value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </x-select>
                                        </div>
                                        <div class="col-md-6">
                                            <x-select name="supplier_id" title="supplier" :required="true"
                                                placeholder="select_a_option">
                                                @foreach ($suppliers as $supplier)
                                                    <option {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                                        value="{{ $supplier->id }}">
                                                        {{ $supplier->name . ' (' . $supplier->company_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </x-select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-fileInputGroup name="document" title="attach_document" :required="false" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-inputGroup name="date" title="purchase_date" type="date"
                                                :required="false" placeholder="" value="{{ date('Y-m-d') }}" />
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="mb-2">{{ __('select_a_product') }}</label>
                                            <div class="search-box input-group">
                                                <span class="btn common-btn"><i class="fa fa-barcode"></i></span>
                                                <input type="text" name="product_code_name" id="searchProduct"
                                                    placeholder="{{ __('please_type_product_code_and_select') }}"
                                                    class="form-control rounded-end" />
                                                <div class="position-absolute w-100 products p-2 shadow" id="productList">
                                                </div>
                                            </div>
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-3">
                                                <table id="myTable" class="table table-hover order-list">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('name') }}</th>
                                                            @if (feature('barcodes'))
                                                                <th>{{ __('code') }}</th>
                                                            @endif
                                                            <th>{{ __('quantity') }}</th>
                                                            <th>{{ __('batch') }}</th>
                                                            <th>{{ __('expired_date') }}</th>
                                                            @if (feature('product_serial_numbers'))
                                                                <th>{{ __('serial_imei_number') }}</th>
                                                            @endif
                                                            <th>{{ __('purchase_cost') }}</th>
                                                            <th>{{ __('tax') }}</th>
                                                            <th>{{ __('sub_total') }}</th>
                                                            <th>{{ __('action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purchaseProduct"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_tax" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-select name="order_tax_rate" :required="false" title="tax" id="payment-method"
                                            placeholder="select_a_option">
                                            @foreach ($taxs as $tax)
                                                <option {{ old('order_tax_rate') == $tax->rate ? 'selected' : '' }}
                                                    value="{{ $tax->rate }}">
                                                    {{ $tax->name }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                        <input type="hidden" name="tax_id">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-2">{{ __('shipping_cost') }}</label>
                                        <input type="text" onkeypress="onlyNumber(event)" name="shipping_cost"
                                            class="form-control mb-2" step="any"
                                            placeholder="{{ __('enter_your_shipping_cost') }}" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-2">{{ __('order_discount') }}</label>
                                        <input type="text" onkeypress="onlyNumber(event)" name="order_discount"
                                            class="form-control" step="any"
                                            placeholder="{{ __('enter_your_discount_amount') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <thead class="table-bg-color text-center">
                                            <tr>
                                                <th>{{ __('total_item') }}<br>
                                                    <p><span class="pull-right" data-purchase-product="0"
                                                            id="totalQtyProduct">(0)</span></p>
                                                    <input type="hidden" name="total_qty" />
                                                </th>
                                                <th>{{ __('total_product') }}<br>
                                                    <p><span class="pull-right" data-product="0"
                                                            id="totalProduct">0</span></p>
                                                    <input type="hidden" name="item" id="item" />
                                                </th>
                                                <th>{{ __('sub_total') }}<br>
                                                    <span style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="subTotal">0.00</span>
                                                    <input type="hidden" name="total_cost" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">{{ __('order_tax') }}</td>
                                                <td class="text-center">
                                                    <span style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="order_tax">0.00</span>
                                                </td>
                                                <input type="hidden" name="order_tax" />
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ __('order_discount') }}</td>
                                                <td class="text-center">
                                                    <span style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="order_discount">0.00</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ __('shipping_cost') }}</td>
                                                <td class="text-center">
                                                    <span style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="shipping_cost">0.00</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-bg-color">
                                            <tr>
                                                <td colspan="2">{{ __('grand_total') }}</td>
                                                <td class="text-center">
                                                    <span style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="grand_total">0.00</span>
                                                </td>
                                                <input type="hidden" name="grand_total" />
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-select name="payment_method" :required="false" title="{{ __('payment_method') }}"
                                        id="payment-method" placeholder="{{ __('select_a_option') }}">
                                        @foreach ($paymentMethods as $paymentMethod)
                                            <option value="{{ $paymentMethod->value }}">{{ $paymentMethod->value }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="mb-2" for="amount">{{ __('amount') }}</label>
                                        <input onkeypress="onlyNumber(event)" type="text" id="amount"
                                            class="form-control" placeholder="{{ __('enter_your_payable_amount') }}"
                                            name="paid_amount">
                                        @error('paid_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3" id="account-list"
                                    style="display: {{ old('payment_method') != 'Bank' ? 'none' : '' }}">
                                    <x-select name="account_id" :required="true" title="{{ __('bank_account') }}"
                                        placeholder="{{ __('select_a_option') }}">
                                        @foreach ($accounts as $account)
                                            <option {{ old('account_id') == $account->id ? 'selected' : '' }}
                                                value="{{ $account->id }}">
                                                {{ $account->name }} ({{ $account->account_no }})
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card my-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('note') }}</label>
                                        <textarea name="note" id="summernote"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="row  mt-3">
                                        <div class="col-12 col-md-6">
                                            <button type="reset"
                                                class="btn mb-sm-2 reset-btn w-100">{{ __('reset') }}</button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <button type="button" class="btn common-btn w-100"
                                                id="submit-btn">{{ __('submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <input type="hidden" id="feature_barcodes" value="{{ feature('barcodes') }}">
    </section>
    <style>
        .product-item {
            background: #cccccc1c;
            border-radius: 8px;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .products {
            background-color: #eef1f5 !important;
            max-height: 400px;
            z-index: 999;
            top: 40px;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            display: none;
            overflow-x: hidden;
            overflow-y: scroll;
        }
    </style>
@endsection
@push('scripts')
    <script src="{{ asset('assets/pages/purchase.js') }}"></script>
    <script>
        $('#submit-btn').on('click', function() {
            $('#purchase-form').submit();
        })
        const product_serial_numbers = @json(feature('product_serial_numbers'));
        const barcodes = @json(feature('barcodes'));

        function selecteItem(id) {
            $("#productList").hide();
            $.ajax({
                url: "/product/details",
                type: "GET",
                data: {
                    id: id,
                },
                dataType: "json",
                success: function(response) {
                    const product = response.data.product;
                    if (product) {
                        var purchaseProduct = $(`#productPurchaseRow_${product.id}`);
                        if (purchaseProduct.length) {
                            var qty = Number($(`#productQty_${product.id}`).val());
                            $(`#productQty_${product.id}`).val(qty + 1);
                            countQty();
                            calculateTotal();
                        } else {
                            var name = product.name;
                            if (name.length > 16) {
                                var name = name.substr(0, 16) + " ...";
                            }

                            let serial_imei_number = '';
                            if (product_serial_numbers) {
                                serial_imei_number = `<td>
                                                        No Serial/IMEI
                                                    </td>`;
                            }
                            let batch = `<td style="width:10px">
                                            <input type="number" class="form-control qty" name="products[${product.id}][qty]"  id="productQty_${product.id}" onchange="countQty()" value="1">
                                        </td>
                                        <td>
                                            No batch
                                        </td>
                                        <td>
                                            No expire date
                                        </td>
                                        ${serial_imei_number}
                                        `;
                            if (product.batch) {
                                batch = `<td style="width:10px">
                                            <input type="number" class="form-control qty" name="products[${product.id}][qty]"  id="productQty_${product.id}" onchange="countQty()" value="1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="products[${product.id}][batch]" placeholder="Batch Number">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" name="products[${product.id}][expire_date]">
                                        </td>
                                        ${serial_imei_number}`;
                            } else if (product.serial_imei_number && product_serial_numbers) {
                                batch = `<td style="width:10px">
                                            <input type="number" class="form-control qty" name="products[${product.id}][qty]"  id="productQty_${product.id}" onchange="countQty()" value="0">
                                        </td>
                                        <td>
                                            No batch
                                        </td>
                                        <td>
                                            No expire date
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm common-btn" data-toggle="modal" data-target="#productAddSerialNumberModal_${product.id}">Serial/IMEI</button>

                                            <div id="productAddSerialNumberModal_${product.id}" tabindex="-1" role="dialog" data-backdrop="static"
                                                aria-labelledby="productAddSerialNumberModalLabel_${product.id}" aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <x-modal-header header="add_product_serial_number" id="productAddSerialNumberModalLabel_${product.id}" />
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 mt-2 mb-3" id="serial_number">
                                                                    <div class="d-flex gap-2">
                                                                        <div class="w-100">
                                                                            <x-inputGroup type="text" name="serial_or_imei_number_${product.id}"
                                                                                title="serial_or_imei_number"
                                                                                placeholder="enter_your_serial_or_imei_number"
                                                                                :required="true" value="" />
                                                                        </div>
                                                                        <div class="d-flex align-items-center mt-4">
                                                                            <button type="button" class="btn common-btn" onclick="addSerialNumber(${product.id})"><i
                                                                                    class="fa fa-check"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive mt-2">
                                                                        <table id="serial-number-table-${product.id}" class="table table-hover">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><i class="fa fa-circle"></i></th>
                                                                                    <th>{{ __('serial_or_imei_number') }}</th>
                                                                                    <th><i class="fa fa-trash"></i></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn common-btn" data-dismiss="modal">{{ __('confirm') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>`;
                            }
                            let code = "";
                            if (barcodes == 1) {
                                code = `<td>${product.code}</td>`;
                            }
                            $("#searchProduct").val("");
                            $("#purchaseProduct")
                                .append(`<tr class="productPurchaseRow" id="productPurchaseRow_${product.id}" data-id="${product.id}">
                        <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                        <td style="width:150px">${name}</td>
                        ${code}

                       ${batch}
                        <td class="net_unit_cost">${product.cost}</td>
                        <input type="hidden" name="products[${product.id}][netUnitCost]" value="${product.cost}">
                        <td class="tax">${product.tax}</td>
                        <input type="hidden" name="products[${product.id}][tax]" value="${product.tax}">

                        <td class="sub-total">${product.costSubtotal}</td>
                        <input type="hidden" name="products[${product.id}][subTotal]" class="subTotal" value="">
                        <td class="d-flex">
                            <button style="font-size:20px" type="button" class="btn text-danger" onclick='deleteRow("${product.id}")'><i class="fa fa-times"></i></button>
                        </td>
                    </tr>`);
                            countQty();
                            calculateTotal();
                        }
                    }
                },
            });
        }

        function addSerialNumber(id) {
            const productQty_id = $("#productQty_" + id).val();
            $("#productQty_" + id).val(Number(productQty_id) + 1);
            countQty();
            var serial_or_imei_number = $(
                "input[name='serial_or_imei_number_" + id + "']"
            ).val();
            var randomNumber = Math.floor(100000 + Math.random() * 900000);
            $("#serial-number-table-" + id)
                .append(`<tr class="serial-number-table-row-${randomNumber}">
                                            <td><i class="fa fa-check-circle text-success"></i></td>
                                            <td>
                                                <input type="hidden" name="products[${id}][serial_or_imei_number][]" value="${serial_or_imei_number}">
                                                ${serial_or_imei_number}
                                            </td>
                                            <td><i class="fa fa-times text-danger" onclick="serialOrImeiNumberDelete(${randomNumber},${id})"></i></td>
                                        </tr>`);
            $("input[name='serial_or_imei_number_" + id + "']").val("");
        }

        function serialOrImeiNumberDelete(number, id) {
            const productQty_id = $("#productQty_" + id).val();
            $("#productQty_" + id).val(Number(productQty_id) - 1);
            countQty();
            $(".serial-number-table-row-" + number).remove();
        }
    </script>
@endpush
