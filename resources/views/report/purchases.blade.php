@extends('layout.app')
@section('title', __('purchase_report'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card my-2">
                <form>
                    <div class="row my-4 mx-3">
                        @php
                            $request = request();
                        @endphp
                        <div class="col-md-3 mb-2">
                            <x-input name="start_date" :value="$request->start_date" title="{{ __('start_date') }}"
                                type="date"></x-input>
                        </div>
                        <div class="col-md-3 mb-2">
                            <x-input name="end_date" :value="$request->end_date" title="{{ __('end_date') }}" type="date"></x-input>
                        </div>
                        <div class="col-md-3 mb-2">
                            <x-select name="warehouse_id" :required="false" title="{{ __('warehouse') }}" id="warehouse_id"
                                placeholder="{{ __('select_a_option') }}">
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ $request->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-2">
                                <label></label>
                                <button class="btn common-btn w-100" id="filter-btn"
                                    type="submit">{{ __('filtering') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="{{ __('purchase_report') }}" />
                    <button type="button" class="btn print-btn" onclick="printPurchaseReport()">
                        <i class="fa fa-print"></i>&nbsp;&nbsp;{{ __('print') }}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('supplier') }}</th>
                                    <th>{{ __('grand_total') }}</th>
                                    <th>{{ __('paid') }}</th>
                                    <th>{{ __('due') }}</th>
                                    <th>{{ __('payment_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_grand_total = 0;
                                    $total_paid = 0;
                                    $total_due = 0;
                                @endphp
                                @foreach ($purchases as $purchase)
                                    @php
                                        $due_amount = $purchase->grand_total - $purchase->paid_amount;
                                        $total_grand_total += $purchase->grand_total;
                                        $total_paid += $purchase->paid_amount;
                                        $total_due += $due_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($purchase->date) }}</td>
                                        <td>{{ $purchase->supplier->name }}</td>
                                        <td>{!! numberFormat($purchase->grand_total) !!}</td>
                                        <td>{!! numberFormat($purchase->paid_amount) !!}</td>
                                        <td>{!! numberFormat($due_amount) !!}</td>
                                        <td>
                                            @if (!$purchase->payment_status)
                                                <span class="badge badge-danger">{{ __('due') }}</span>
                                            @else
                                                <span class="badge badge-success">{{ __('paid') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>{{ __('total') }}</strong></td>
                                    <td><strong>{{ numberFormat($total_grand_total) }}</strong></td>
                                    <td><strong>{{ numberFormat($total_paid) }}</strong></td>
                                    <td><strong>{{ numberFormat($total_due) }}</strong></td>
                                    <td></td>
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
    <script type="text/javascript">
        function printPurchaseReport() {
            const length = $('select[name="dataTable_length"]').val();
            window.location.href = "{{ route('purchases.print') }}" + "?length=" + length
        }
    </script>
@endpush
