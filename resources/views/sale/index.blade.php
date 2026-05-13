@extends('layout.app')
@section('title', __('sales'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('sales') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('invoice_no') }}</th>
                                    <th>{{ __('biller') }}</th>
                                    <th>{{ __('total_product') }}</th>
                                    <th>{{ __('total_discount') }}</th>
                                    <th>{{ __('total_tax') }}</th>
                                    <th>{{ __('total_price') }}</th>
                                    <th>{{ __('grand_total') }}</th>
                                    <th>{{ __('payment_method') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($sale->created_at) }}</td>
                                        <td>{{ $sale->reference_no }}</td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>{{ $sale->total_qty }}</td>
                                        <td>{{ numberFormat($sale->total_discount) }}</td>
                                        <td>{{ numberFormat($sale->total_tax) }}</td>
                                        <td>{{ numberFormat($sale->total_price) }}</td>
                                        <td>{{ numberFormat($sale->grand_total) }}</td>
                                        <td>{{ $sale->payment_method }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                                        data-target="#sale_details_{{ $sale->id }}"><i
                                                            class="fa fa-eye text-info"></i>&nbsp;&nbsp;
                                                        {{ __('view') }}</a>
                                                    <a href="{{ route('sale.invoice.generate', $sale->id) }}"
                                                        class="dropdown-item"><i
                                                            class="fa fa-file text-primary"></i>&nbsp;&nbsp;
                                                        {{ __('invoice') }}</a>
                                                </div>
                                            </div>
                                            <div id="sale_details_{{ $sale->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true" class="modal fade text-left saleDetails">
                                                <div role="document" class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="container mt-3 pb-2 border-bottom">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-4">
                                                                    <div class="branding-logo">
                                                                        <img src="{{ $general_settings->logo->file ?? asset('/logo/logo.svg') }}"
                                                                            alt="" style="width:250px">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 text-center">
                                                                    <i
                                                                        style="font-size: 15px;">{{ __('sale_details') }}</i>
                                                                </div>
                                                                <div class="col-md-4 d-print-none">
                                                                    <button type="button" id="close-btn"
                                                                        data-dismiss="modal" aria-label="Close"
                                                                        class="close"><span aria-hidden="true"><i
                                                                                class="fa fa-times"></i></span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="sale-content" class="modal-body">
                                                            <strong>{{ __('date') }}:</strong>
                                                            {{ dateFormat($sale->created_at) }}<br>
                                                            <strong>{{ __('reference') }}:</strong>
                                                            {{ $sale->reference_no }}
                                                            @if ($sale->customer)
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="float-right">
                                                                            <strong>{{ __('to') }}:</strong><br>{{ $sale->customer?->name }}<br>{{ $sale->customer?->email }}<br>{{ $sale->customer?->phone }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <table
                                                                class="table table-bordered product-purchase-list mt-3 p-2">
                                                                <thead class="table-bg-color">
                                                                    <tr>
                                                                        <th>{{ __('sl') }}</th>
                                                                        <th>{{ __('product') }}</th>
                                                                        <th>{{ __('quantity') }}</th>
                                                                        <th>{{ __('tax') }}</th>
                                                                        <th>{{ __('price') }}</th>
                                                                        <th>{{ __('sub_total') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($sale->productSales as $productSale)
                                                                        <tr>
                                                                            <td><strong>{{ $loop->iteration }}</strong>
                                                                            </td>
                                                                            <td>{{ $productSale->product->name }}
                                                                                @if (feature('barcodes'))
                                                                                    [{{ $productSale->product->code }}]
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $productSale->qty }}</td>
                                                                            <td>{{ numberFormat($productSale->tax) }}
                                                                            </td>
                                                                            <td>{{ numberFormat($productSale->net_unit_price) }}
                                                                            </td>
                                                                            <td>{{ numberFormat($productSale->total) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td colspan="5" class="text-right">
                                                                            <strong>{{ __('total') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($sale->total_price) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <strong>{{ __('order_tax') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($sale->order_tax) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <strong>{{ __('order_discount') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($sale->order_discount) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <strong>{{ __('coupon_discount') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($sale->coupon_discount) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <strong>{{ __('grand_total') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($sale->grand_total) }}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div class="row my-2">
                                                                <div class="col-12 border-1">
                                                                    <p><strong>{{ __('note') }}:</strong>
                                                                        {!! $sale->note !!}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <strong>{{ __('billder') }}:</strong><br>{{ $sale->user->name }}<br>{{ $sale->user->email }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        function printCategory() {
            const length = $('select[name="dataTable_length"]').val();
            window.location.href = "{{ route('sale.print') }}" + "?length=" + length
        }
    </script>
@endpush
