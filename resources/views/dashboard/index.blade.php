@extends('layout.app')
@section('title', __('dashboard'))
@section('content')
    <style>
        .graph-card-recurring-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .graph-card-recurring {
            padding: 5px 10px;
            border-radius: 5px;
            background: #efefef;
            box-shadow: 4px 4px 8px 0px rgba(41, 170, 225, 0.1);
            font-weight: 800;
            color: black;
            line-height: 28px;
        }

        .graph-card-recurring.active {
            background-color: #29aae1;
            color: #ffff;
        }

        .daterange-picker {
            background: #efefef;
            font-size: 14px;
            color: #333;
            box-shadow: 4px 4px 8px 0px rgba(41, 170, 225, 0.1);
        }
    </style>
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 my-2">
                    <div class="graph-card-recurring-container">
                        <div class="report-month-card-text">
                            @if (request()->get('type') && request()->get('type') == 'daily')
                                {{ today()->format('M d, Y') }}
                            @elseif (request()->get('type') && request()->get('type') == 'weekly')
                                {{ now()->startOfWeek()->format('M d, Y') }} <span class="text-muted">To</span>
                                {{ now()->endOfWeek()->format('M d, Y') }}
                            @elseif (request()->get('type') && request()->get('type') == 'monthly')
                                {{ now()->startOfMonth()->format('M d, Y') }} <span class="text-muted">To</span>
                                {{ now()->endOfMonth()->format('M d, Y') }}
                            @elseif (request()->get('type') && request()->get('type') == 'yearly')
                                {{ now()->startOfYear()->format('M d, Y') }} <span class="text-muted">To</span>
                                {{ now()->endOfYear()->format('M d, Y') }}
                            @else
                                {{ __('total') }}
                            @endif
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('root', ['type' => 'daily']) }}"
                                class="graph-card-recurring {{ request()->get('type') && request()->get('type') == 'daily' ? ' active' : '' }}">{{ __('daily') }}</a>
                            <a href="{{ route('root', ['type' => 'weekly']) }}"
                                class="graph-card-recurring {{ request()->get('type') && request()->get('type') == 'weekly' ? ' active' : '' }}">{{ __('weekly') }}</a>
                            <a href="{{ route('root', ['type' => 'monthly']) }}"
                                class="graph-card-recurring {{ request()->get('type') && request()->get('type') == 'monthly' ? ' active' : '' }}">{{ __('monthly') }}</a>
                            <a href="{{ route('root', ['type' => 'yearly']) }}"
                                class="graph-card-recurring {{ request()->get('type') && request()->get('type') == 'yearly' ? ' active' : '' }}">{{ __('yearly') }}</a>
                            <input type="text" name="daterange" class="form-control daterange-picker"
                                value="{{ Carbon\Carbon::parse(request()->get('start_date'))->format('m/d/Y') }} - {{ Carbon\Carbon::parse(request()->get('end_date'))->format('m/d/Y') }}" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('total_sales') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/hart-histogram_2.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($sales->sum('grand_total')) }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('net_profit') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/profit.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($monthlyNetProfit) }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('gross_profit') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/profit_2.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($monthlyGrossProfit) }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('sales_return') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/sales_returen.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($saleReturns->sum('grand_total')) }}</div>
                    </div>
                </div>
                @if (feature('purchases'))
                    <div class="col-lg-3 mb-3">
                        <div class="report-section-card">
                            <div class="report-section-card-body">
                                <div class="report-section-card-text">
                                    {{ __('purchase') }}
                                </div>
                                <div class="report-section-card-image">
                                    <img src="{{ asset('icons/purchase_2.svg') }}" alt="">
                                </div>
                            </div>
                            <div class="report-section-card-price">{{ numberFormat($purchases->sum('grand_total')) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="report-section-card">
                            <div class="report-section-card-body">
                                <div class="report-section-card-text">
                                    {{ __('purchase_due') }}
                                </div>
                                <div class="report-section-card-image">
                                    <img src="{{ asset('icons/money-coin_2.svg') }}" alt="">
                                </div>
                            </div>
                            <div class="report-section-card-price">
                                {{ numberFormat($purchases->sum('grand_total') - $purchases->sum('paid_amount')) }}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('expense') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/expense.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($expenses->sum('amount')) }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="report-section-card">
                        <div class="report-section-card-body">
                            <div class="report-section-card-text">
                                {{ __('salary_cost') }}
                            </div>
                            <div class="report-section-card-image">
                                <img src="{{ asset('icons/salary.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="report-section-card-price">{{ numberFormat($payrolls->sum('amount')) }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-8">
                    @if (feature('purchases'))
                        <div class="graph-card">
                            <div class="graph-card-text">{{ __('purchase_and_sale_flow') }}</div>
                            <div id="purchaseSaleChart"></div>
                        </div>
                    @endif
                    <div class="graph-card mt-3">
                        <div class="graph-card-text">{{ __('cash_flow') }}</div>
                        <div id="payment"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="best-sale-monthly-card">
                        <div class="best-sale-monthly">
                            <div class="best-sale-monthly-text">{{ __('sales_in') }} {{ date('F') }}</div>
                        </div>
                        <div class="table-responsive w-100">
                            <table class="table table-borderless table-responsive-md best-sell-table">
                                <tr class="best-sale-monthly-table-tr">
                                    <th class="border-r-0">{{ __('product_details') }}</th>
                                    <th class="text-center">{{ __('qty') }}</th>
                                </tr>
                                @foreach ($productSales as $sale)
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="best-sale-monthly-image">
                                                    <img src="{{ $sale->product->thumbnail?->file }}" width="33px"
                                                        alt="">
                                                </div>
                                                <div class="mt-2">{{ Str::limit($sale->product->name, 30, '...') }}
                                                    @if (feature('barcodes'))
                                                        [{{ $sale->product->code }}]
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $sale->total_quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    @if (feature('purchases'))
                        <div class="best-sale-monthly-card mt-3">
                            <div class="best-sale-monthly">
                                <div class="best-sale-monthly-text">{{ __('purchase_in') }} {{ date('F') }}</div>
                            </div>
                            <div class="w-100 table-responsive">
                                <table class="table table-borderless table-responsive-md best-sell-table">
                                    <tr class="best-sale-monthly-table-tr">
                                        <th class="border-r-0">{{ __('product_details') }}</th>
                                        <th class="text-center">{{ __('qty') }}</th>
                                    </tr>
                                    @foreach ($productPurchases as $purchase)
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="best-sale-monthly-image">
                                                        <img src="{{ $purchase->product->thumbnail?->file }}"
                                                            width="33px" alt="">
                                                    </div>
                                                    <div class="mt-2">
                                                        {{ Str::limit($purchase->product->name, 30, '...') }}
                                                        @if (feature('barcodes'))
                                                            [{{ $purchase->product->code }}]
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                {{ $purchase->total_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-12">
                    <div class="best-sale-monthly-card">
                        <div class="best-sale-monthly">
                            <div class="best-sale-monthly-text">{{ __('recent_transaction') }}</div>
                        </div>
                        <div class="w-100 table-responsive">
                            <table class="table table-borderless table-responsive-md best-sell-table">
                                <tr class="best-sale-monthly-table-tr">
                                    <th class="border-r-0">{{ __('date') }}</th>
                                    <th>{{ __('payment_method') }}</th>
                                    <th>{{ __('translation_by') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('amount') }}</th>
                                </tr>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ dateFormat($transaction->date) }}</td>
                                        <td>{{ $transaction->payment_method }}</td>
                                        <td>{{ $transaction->user->name }}</td>
                                        <td>
                                            @if ($transaction->transection_type->value == 'Debit')
                                                <span
                                                    class="transaction-status">{{ $transaction->transection_type->value }}</span>
                                            @else
                                                <span
                                                    class="transaction-status-cradit">{{ $transaction->transection_type->value }}</span>
                                            @endif
                                        </td>
                                        <td>{{ numberFormat($transaction->amount) }}</td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        const formattedSales = @json($formattedSales);
        const formattedPurchases = @json($formattedPurchases);
        const currency = @json($currency->symbol ?? '$');
        const formattedDabits = @json($formattedDabits);
        const formattedCradits = @json($formattedCradits);
        const themeColor = @json($themeColor);

        var urlParams = new URLSearchParams(window.location.search);
        var param1Value = urlParams.get('type');
        let datetype = [];
        if (param1Value == 'daily') {
            datetype = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', ]
        } else if (param1Value == 'weekly') {
            datetype = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        } else if (param1Value == 'monthly') {
            datetype = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16',
                '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'
            ]
        } else {
            datetype = ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'];
        }

        var options = {
            series: [{
                name: 'Sales',
                data: formattedSales
            }, {
                name: 'Purchase',
                data: formattedPurchases
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            theme: {
                mode: themeColor // Setting the theme mode to 'dark'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: datetype,
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return currency + " " + val
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#purchaseSaleChart"), options);
        chart.render();

        var options = {
            series: [{
                name: "Debit",
                data: formattedDabits
            }, {
                name: "Credit",
                data: formattedCradits
            }],

            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            theme: {
                mode: themeColor // Setting the theme mode to 'dark'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: datetype,
            }
        };

        var chart = new ApexCharts(document.querySelector("#payment"), options);
        chart.render();

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'

            }, function(start, end, label) {
                window.location.href = "{{ route('root') }}" + "?start_date=" + start.format(
                        'YYYY-MM-DD') +
                    "&end_date=" + end.format('YYYY-MM-DD');
            });
        });
    </script>

    @if (session('token'))
        <script>
            localStorage.setItem("token", "{{ session('token') }}");
            localStorage.setItem("name", "{{ auth()->user()->name }}");
            localStorage.setItem("role", "{{ auth()->user()->roles[0]->name }}");
            localStorage.setItem("image", "{{ auth()->user()->thumbnail->file ?? null }}");
        </script>
    @endif
@endpush
