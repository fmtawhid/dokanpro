@extends('layout.app')
@section('title', __('profit_loss_report'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
                    <x-section-header title="{{ __('profit_loss_report') }}" />
                </div>
                <div class="card-body">
                    <form action="{{ route('report.profit.loss') }}" method="GET">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <x-select name="report_type" title="report_type" placeholder="select_a_option"
                                        id="report_type" :required="true">
                                        <option {{ request('report_type') == 1 ? 'selected' : '' }} value="1">
                                            {{ __('gross_profit_loss') }} </option>
                                        <option {{ request('report_type') == 2 ? 'selected' : '' }} value="2">
                                            {{ __('net_profit_loss') }} </option>
                                    </x-select>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="mb-2" for="daterange-picker">{{ __('date_range') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="daterange" class="form-control daterange-picker mr-2"
                                            id="daterange-picker"
                                            value="{{ Carbon\Carbon::parse(request()->get('start_date'))->format('m/d/Y') }} - {{ Carbon\Carbon::parse(request()->get('end_date'))->format('m/d/Y') }}" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-right">
                                        <button type="submit" class="btn common-btn w-100"
                                            style="margin-top: 32px">{{ __('show') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (count($saleProducts) > 0)
                        <div class="row position-relative">
                            <div class="table-responsive table-custom">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('sl') }}</th>
                                            <th>{{ __('name') }}</th>
                                            <th>{{ __('avg_purchase_price') }}</th>
                                            <th>{{ __('avg_selling_price') }}</th>
                                            <th>{{ __('avg_tax') }}</th>
                                            <th>{{ __('avg_subtotal') }}</th>
                                            <th>{{ __('sold_qty') }}</th>
                                            <th class="text-right">
                                                <strong>
                                                    <span class="text-success">{{ __('profit') }}</span> /
                                                    <span class="text-danger">{{ __('loss') }}</span>
                                                </strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalQty = 0;
                                            $totalProfitLoss = 0;
                                        @endphp
                                        @foreach ($saleProducts as $saleProduct)
                                            @php
                                                $tax =
                                                    ($saleProduct->product->price * $saleProduct->product?->tax?->rate) /
                                                    100;
                                                $profitLoss =
                                                    $saleProduct->product->price - $saleProduct->product->cost;

                                                $totalQty += $saleProduct->total_qty;
                                                $totalProfitLoss += $profitLoss;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <b>{{ $saleProduct->product->name }}</b>
                                                </td>
                                                <td>{{ numberFormat($saleProduct->product->cost) }}</td>
                                                <td>{{ numberFormat($saleProduct->product->price) }}</td>
                                                <td>{{ numberFormat($tax) }}</td>
                                                <td>{{ numberFormat($saleProduct->product->price + $tax) }}
                                                </td>
                                                <td>{{ $saleProduct->total_qty }}</td>
                                                <td class="text-right">
                                                    <strong>
                                                        @if ($profitLoss > 0)
                                                            <span
                                                                class="text-success">{{ numberFormat($profitLoss) }}</span>
                                                        @else
                                                            <span
                                                                class="text-danger">{{ numberFormat($profitLoss) }}</span>
                                                        @endif
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-right">
                                                <strong>{{ __('total') }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $totalQty }}</strong>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ numberFormat($totalProfitLoss) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif (count($sales) > 0 || count($purchases) > 0)
                        @php
                            $dates = explode(' - ', request('daterange'));
                            $totalCost =
                                $sales->sum('grand_total') +
                                $sales->sum('order_discount') -
                                $sales->sum('shipping_cost') -
                                $sales->sum('order_tax');
                        @endphp
                        <div class="row position-relative">
                            <div class="table-responsive table-custom">
                                <table class="table text-left">
                                    <thead>
                                        <tr class="text-center">
                                            <td colspan="3">
                                                <strong>
                                                    {{ __('from') }}
                                                    <span class="text-primary">{{ $dates[0] }}</span>
                                                    {{ __('to') }}
                                                    <span class="text-primary">{{ $dates[1] }}</span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{{ __('total_sales') }}</th>
                                            <td></td>
                                            <td class="text-right">
                                                <strong>{{ numberFormat($sales->sum('grand_total')) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('cost_of_goods_sold') }}</th>
                                            <td></td>
                                            <td class="text-right">
                                                <u>
                                                    <strong>({{ numberFormat($totalCost) }})</strong>
                                                </u>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                @if ($totalCost - $sales->sum('grand_total') > 0)
                                                    <span class="text-success">{{ __('gross_profit') }}</span>
                                                @else
                                                    <span class="text-danger">{{ __('gross_loss') }}</span>
                                                @endif
                                            </th>
                                            <td></td>
                                            <td class="text-right">
                                                <strong>{{ numberFormat($totalCost - $sales->sum('grand_total')) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-secondary text-center">
                                    <h5>
                                        <i class="fa-solid fa-info-circle"></i>
                                        {{ __('empty_msg_title') }}
                                    </h5>
                                    {{ __('empty_msg_text') }}
                                </div>
                            </div>
                        </div>
                    @endif
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
            });
        });
    </script>
@endpush
