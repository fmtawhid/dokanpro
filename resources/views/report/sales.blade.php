@extends('layout.app')
@section('title', __('sales_report'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <x-section-header title="{{ __('sales_report') }}" />

                    <div class="d-flex align-items-center">
                        <input type="text" name="daterange" class="form-control daterange-picker mr-2"
                            value="{{ Carbon\Carbon::parse(request()->get('start_date'))->format('m/d/Y') }} - {{ Carbon\Carbon::parse(request()->get('end_date'))->format('m/d/Y') }}" />
                        <button type="button" class="btn d-inline-flex print-btn" onclick="printSalesReport()">
                            <i class="fa fa-print"></i>&nbsp;&nbsp;{{ __('print') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('biller') }}</th>
                                    <th>{{ __('total_product') }}</th>
                                    <th>{{ __('total_discount') }}</th>
                                    <th>{{ __('total_tax') }}</th>
                                    <th>{{ __('total_price') }}</th>
                                    <th>{{ __('grand_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($sale->created_at) }}</td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>{{ $sale->total_qty }}</td>
                                        <td>{{ numberFormat($sale->total_discount) }}</td>
                                        <td>{{ numberFormat($sale->total_tax) }}</td>
                                        <td>{{ numberFormat($sale->total_price) }}</td>
                                        <td>{{ numberFormat($sale->grand_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ __('total') }}</th>
                                    <th></th>
                                    <th></th>
                                    <th>{{ $sales->sum('total_qty') }}</th>
                                    <th>{{ numberFormat($sales->sum('total_discount')) }}</th>
                                    <th>{{ numberFormat($sales->sum('total_tax')) }}</th>
                                    <th>{{ numberFormat($sales->sum('total_price')) }}</th>
                                    <th>{{ numberFormat($sales->sum('grand_total')) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                window.location.href = "{{ route('report.sales') }}" + "?start_date=" + start.format(
                        'YYYY-MM-DD') +
                    "&end_date=" + end.format('YYYY-MM-DD');
            });
        });

        function printSalesReport() {
            var start = $('input[name="daterange"]').data('daterangepicker').startDate.format(
                'YYYY-MM-DD') ?? '';
            var end = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD') ?? '';

            window.location.href = "{{ route('sale.print') }}" + "?start_date=" + start +
                "&end_date=" + end;
        }
    </script>
@endpush
