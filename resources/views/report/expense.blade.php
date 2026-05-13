@extends('layout.app')
@section('title', __('expense_report'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
                    <x-section-header title="{{ __('expense_report') }}" />
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div>
                            <input type="text" name="daterange" class="form-control daterange-picker mr-2"
                                value="{{ Carbon\Carbon::parse(request()->get('start_date'))->format('m/d/Y') }} - {{ Carbon\Carbon::parse(request()->get('end_date'))->format('m/d/Y') }}" />
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th>{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('warehouse') }}</th>
                                    <th>{{ __('category') }}</th>
                                    <th>{{ __('amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $key => $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($expense->created_at) }}
                                        </td>
                                        <td>{{ $expense->reference_no }}</td>
                                        <td>{{ $expense->warehouse->name }}</td>
                                        <td>{{ $expense->expenseCategory->name }}</td>
                                        <td>{{ numberFormat($expense->amount) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="5"><strong>{{ __('total') }}</strong></th>
                                    <th colspan="2"><strong>{{ numberFormat($expenses->sum('amount')) }}</strong></th>
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
                window.location.href = "{{ route('expense.index') }}" + "?start_date=" + start +
                    "&end_date=" + end;
            });
        });
    </script>
@endpush
