@extends('layout.app')
@section('title', __('product_barcode'))
@section('content')
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

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                size: landscape;
                margin: 0 !important;
            }

            .barcodelist {
                max-width: 378px;
            }

            .barcodelist img {
                max-width: 150px;
            }
        }
    </style>

    <section class="forms">
        <div class="container-fluid">
            <form action="{{ route('product.barcode.generate') }}" method="post" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-lg-7 col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header d-flex align-items-center card-header-color">
                                <span class="list-title text-white">{{ __('print_barcode') }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="mb-2">{{ __('add_product') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="search-box input-group">
                                                    <button type="button" class="btn common-btn btn-lg"><i
                                                            class="fa fa-barcode"></i></button>
                                                    <input type="text" name="product_ids" id="searchProduct"
                                                        placeholder="{{ __('please_type_product_code_and_select') }}"
                                                        class="form-control" />
                                                    <div class="position-absolute w-100 products p-2 shadow"
                                                        id="productList">
                                                    </div>
                                                </div>
                                                @error('product_ids')
                                                    <span class="text-danger">{{ __('please_select_a_product') }}</span>
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
                                                                <th>{{ __('code') }}</th>
                                                                <th>{{ __('quantity') }}</th>
                                                                <th>{{ __('action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="productBarcode">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="card p-4">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input name="name" checked class="form-check-input" type="checkbox"
                                            id="name" value="1">
                                        <label class="form-check-label" for="name">
                                            {{ __('name') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input name="price" checked class="form-check-input" type="checkbox"
                                            id="price" value="1">
                                        <label class="form-check-label" for="price">
                                            {{ __('price') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <input name="promo_price" class="form-check-input" type="checkbox"
                                            id="promo_price" value="1">
                                        <label class="form-check-label" for="promo_price">
                                            {{ __('promotional_price') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <x-select name="size" title="size" :required="true" placeholder="select_a_option">
                                        <option value="row">{{ __('row') }}</option>
                                        <option value="single">{{ __('single') }}</option>
                                    </x-select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn common-btn w-100">{{ __('print') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).mouseup(function(e) {
            var container = $('#productList');
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
                $('#searchProduct').val('');
            }
        });

        $('#searchProduct').on("keyup", function() {
            var value = $('#searchProduct').val();
            if (value == '') {
                $('#productList').hide();
                $('#productList').html('');
            }
            $.ajax({
                url: "{{ route('product.search') }}",
                type: 'GET',
                data: {
                    search: value
                },
                dataType: 'json',
                success: function(response) {
                    if (response.data.products.length) {
                        $('#productList').show()
                        let html = '';
                        $.each(response.data.products, function(index, item) {
                            html +=
                                `<div class='product-item p-2' onclick='selecteItem("${item.id}")'>${item.name}</div>`
                        });
                        $('#productList').html(html)

                    }
                }
            });
        });

        function selecteItem(id) {
            $('#productList').hide()
            $.ajax({
                url: "{{ route('product.details') }}",
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    const product = response.data.product;
                    if (product) {
                        var products = $(`#productPurchaseRow_${product.id}`);
                        if (products.length) {
                            var qty = Number($(`#productQty_${product.id}`).val());
                            $(`#productQty_${product.id}`).val(qty + 1);
                            countQty();
                        } else {
                            $('#searchProduct').val('');
                            $('#productBarcode').append(`<tr class="productPurchaseRow" id="productPurchaseRow_${product.id}" data-id="${product.id}" data-total="0">
                                <input type="hidden" name="product_ids[]" value="${product.id}">
                                <td>${product.name}</td>
                                <td>${product.code}</td>
                                <td>
                                    <input type="number" class="form-control qty" name="qtys[]"  id="productQty_${product.id}" onchange="countQty()" value="1">

                                </td>
                                <td>
                                    <a onclick='deleteRow("${product.id}")'><i class="fa fa-times text-danger"></i></a>
                                </td>
                            </tr>`);
                            countQty();
                        }
                    }
                }
            });
        }

        function deleteRow(id) {
            $('#productPurchaseRow_' + id).remove();
            countQty();
        }

        countQty = function() {
            let totalElement = document.getElementsByClassName('productPurchaseRow')
            var totalQty = 0
            for (var i = 0; i < totalElement.length; i++) {
                var totalQty = (Number(totalQty) + Number(totalElement[i].getElementsByClassName('qty')[0].value));
            }
        }
    </script>
@endpush
