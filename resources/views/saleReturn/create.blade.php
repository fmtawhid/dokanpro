@extends('layout.app')
@section('title', __('sale_returns'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <form action="{{ route('sale.return.product.store', $sale->id) }}" method="POST" id="purchase-form"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="myTable" class="table table-hover order-list">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('name') }}</th>
                                                                @if (feature('barcodes'))
                                                                    <th>{{ __('code') }}</th>
                                                                @endif
                                                                <th>{{ __('quantity') }}</th>
                                                                <th>{{ __('purchase_cost') }}</th>
                                                                <th>{{ __('tax') }}</th>
                                                                <th>{{ __('sub_total') }}</th>
                                                                <th>{{ __('action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="purchaseProduct">
                                                            @foreach ($sale->productSales as $product)
                                                                <tr class="productPurchaseRow"
                                                                    id="productPurchaseRow_{{ $product->product->id }}"
                                                                    data-id="{{ $product->product->id }}">
                                                                    <input type="hidden"
                                                                        name="products[{{ $product->product->id }}][id]"
                                                                        value="{{ $product->product->id }}">
                                                                    <td>{{ substr($product->product->name, 0, 20) }}
                                                                    </td>
                                                                    @if (feature('barcodes'))
                                                                        <td>{{ $product->product->code }}</td>
                                                                    @endif
                                                                    <td>
                                                                        <input type="number" class="form-control qty"
                                                                            name="products[{{ $product->product->id }}][qty]"
                                                                            id="productQty_{{ $product->product->id }}"
                                                                            onchange="countQty()"
                                                                            value="{{ $product->qty }}">
                                                                    </td>
                                                                    <td class="net_unit_cost">
                                                                        {{ $product->net_unit_price }}</td>
                                                                    <input type="hidden"
                                                                        name="products[{{ $product->product->id }}][netUnitCost]"
                                                                        value="{{ $product->net_unit_price }}">
                                                                    <td class="tax">{{ $product->tax }}</td>
                                                                    <input type="hidden"
                                                                        name="products[{{ $product->product->id }}][tax]"
                                                                        id="tax" value="{{ $product->tax }}">

                                                                    <td class="sub-total">{{ $product->total }}
                                                                    </td>
                                                                    <input type="hidden"
                                                                        name="products[{{ $product->product->id }}][subTotal]"
                                                                        class="subTotal" value="{{ $product->total }}">
                                                                    <td class="d-flex">
                                                                        <button style="font-size:20px" type="button"
                                                                            class="btn text-danger"
                                                                            onclick='deleteRow("{{ $product->product->id }}")'><i
                                                                                class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">

                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="total_discount"
                                                        value="{{ $sale->total_discount }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="total_tax"
                                                        value="{{ $sale->total_tax }}" />
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
                                    <table class="table table-hover">
                                        <thead class="table-bg-color text-center">
                                            <tr>
                                                <th>{{ __('total_item') }}<br>
                                                    <p><span class="pull-right" data-purchase-product="0"
                                                            id="totalQtyProduct">({{ $sale->total_qty }})</span></p>
                                                    <input type="hidden" name="total_qty"
                                                        value="{{ $sale->total_qty }}" />
                                                </th>
                                                <th>{{ __('total_product') }}<br>
                                                    <p><span class="pull-right" data-product="0"
                                                            id="totalProduct">{{ $sale->item }}</span></p>
                                                    <input type="hidden" name="item" id="item"
                                                        value="{{ $sale->item }}" />
                                                </th>
                                                <th>{{ __('sub_total') }}<br>
                                                    <span
                                                        style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="subTotal">{{ $sale->total_price }}</span>
                                                    <input type="hidden" name="total_price"
                                                        value="{{ $sale->total_price }}" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">{{ __('order_tax') }}</td>
                                                <td class="text-center">
                                                    <span
                                                        style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="order_tax">{{ $sale->order_tax_rate ?? '0.00' }}</span>
                                                </td>
                                                <input type="hidden" name="order_tax"
                                                    value="{{ $sale->order_tax_rate ?? '0.00' }}" />
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ __('order_discount') }}</td>
                                                <td class="text-center">
                                                    <span
                                                        style="font-size: 15px;">{{$currency->symbol ?? '$' }}</span>
                                                    <span
                                                        id="order_discount">{{ $sale->order_discount ?? '0.00' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ __('shipping_cost') }}</td>
                                                <td class="text-center">
                                                    <span
                                                        style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span
                                                        id="shipping_cost">{{ $sale->order_discount ?? '0.00' }}</span>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tfoot class="table-bg-color">
                                            <tr>
                                                <td colspan="2">{{ __('grand_total') }}</td>
                                                <td class="text-center">
                                                    <span
                                                        style="font-size: 15px;">{{ $currency->symbol ?? '$' }}</span>
                                                    <span id="grand_total">{{ $sale->grand_total ?? '0.00' }}</span>
                                                </td>
                                                <input type="hidden" name="grand_total"
                                                    value="{{ $sale->grand_total }}" />
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('note') }}</label>
                                    <textarea name="note" id="summernote">{!! $sale->note !!}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="row  mt-3">
                                    <div class="col-12 col-md-6">
                                        <a href="{{ route('purchase.index') }}"
                                            class="btn mb-sm-2 reset-btn w-100">{{ __('back') }}</a>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <button type="submit" class="btn common-btn w-100">{{ __('return') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
@endpush
