@extends('layout.app')
@section('title', __('product_report'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="{{ __('product_report') }}" />
                    <div class="d-flex align-items-center">
                        <input type="text" name="daterange" class="form-control daterange-picker mr-2"
                            value="{{ Carbon\Carbon::parse(request()->get('start_date'))->format('m/d/Y') }} - {{ Carbon\Carbon::parse(request()->get('end_date'))->format('m/d/Y') }}" />
                        <div class="dropdown" style="margin-left: 10px">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown">
                                {{ request()->status ?? __('select_a_option') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('report.product') }}">{{ __('all_produts') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('report.product', ['status' => 'standard']) }}">{{ __('standard') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('report.product', ['status' => 'combo']) }}">{{ __('combo') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('report.product', ['status' => 'digital']) }}">{{ __('digital') }}</a>
                            </div>
                        </div>
                        <button type="button" class="btn d-inline-flex print-btn" onclick="printProduct()"><i
                                class="fa fa-print"></i>&nbsp;&nbsp;{{ __('print') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th>{{ __('sl') }}</th>
                                    <th width="300px">{{ __('product') }}</th>
                                    <th>{{ __('total_stock') }}</th>
                                    @if (feature('purchases'))
                                        <th>{{ __('total_purchase') }}</th>
                                        <th>{{ __('total_purchase_price') }}</th>
                                    @endif
                                    <th>{{ __('total_sale') }}</th>
                                    <th>{{ __('total_selling_price') }}</th>
                                    <th>{{ __('total_sale_return') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_purchase = 0;
                                    $total_purchase_price = 0;
                                    $total_sale = 0;
                                    $total_selling_price = 0;
                                    $total_sale_return = 0;
                                @endphp
                                @foreach ($products as $product)
                                    @php
                                        $total_purchase += $product->purchaseProducts()->sum('qty');
                                        $total_purchase_price += $product->purchaseProducts()->sum('total');
                                        $total_sale += $product->saleProducts()->sum('qty');
                                        $total_selling_price += $product->saleProducts()->sum('total');
                                        $total_sale_return += $product->saleReturnProducts()->sum('qty');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <img src="{{ $product->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                                    height="30" width="30">
                                                <div>{{ $product->name }}</div>
                                            </div>
                                        </td>
                                        @if (feature('purchases'))
                                            <td>{{ $product->qty }}</td>
                                            <td>{{ $product->purchaseProducts()->sum('qty') }}</td>
                                        @endif
                                        <td>{{ numberFormat($product->purchaseProducts()->sum('total')) }}</td>
                                        <td>{{ $product->saleProducts()->sum('qty') }}</td>
                                        <td>{{ numberFormat($product->saleProducts()->sum('total')) }}</td>
                                        <td>{{ $product->saleReturnProducts()->sum('qty') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="{{ feature('purchases') ? 4 : 2 }}"><strong>{{ __('total') }}</strong>
                                    </td>
                                    <td colspan="{{ feature('purchases') ? 2 : 2 }}">
                                        <strong>{{ numberFormat($total_purchase_price) }}</strong></td>
                                    <td colspan="{{ feature('purchases') ? 2 : 3 }}">
                                        <strong>{{ numberFormat($total_selling_price) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                window.location.href = "{{ route('report.product') }}" + "?start_date=" + start.format(
                        'YYYY-MM-DD') +
                    "&end_date=" + end.format('YYYY-MM-DD');
            });
        });

        function printProduct() {
            // var start = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD') ?? '';
            // var end = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD') ?? '';
            var start = '';
            var end = '';
            window.location.href = "{{ route('product.print') }}" + "?start_date=" + start +
                "&end_date=" + end;
        }
    </script>
@endpush
