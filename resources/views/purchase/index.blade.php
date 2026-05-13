@extends('layout.app')
@section('title', __('purchases'))
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
                    <span class="list-title">{{ __('purchases') }}</span>
                    <div>
                        <a href="{{ route('purchase.create') }}" class="btn common-btn"><i
                                class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_purchase') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('supplier') }}</th>
                                    <th>{{ __('grand_total') }}</th>
                                    <th>{{ __('paid') }}</th>
                                    <th>{{ __('due') }}</th>
                                    <th>{{ __('payment_status') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    @php
                                        $due_amount = $purchase->grand_total - $purchase->paid_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($purchase->date) }}</td>
                                        <td>{{ $purchase->reference_no }}</td>
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
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="">

                                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                                        data-target="#purchase-details_{{ $purchase->id }}"><i
                                                            class="fa fa-eye text-info"></i>&nbsp;&nbsp;
                                                        {{ __('view') }}</a>
                                                    <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                        class="dropdown-item"><i
                                                            class="fa fa-edit text-primary"></i>&nbsp;&nbsp;
                                                        {{ __('edit') }}</a>

                                                    @if (!$purchase->payment_status)
                                                        <a href="#" class="add-payment dropdown-item" data-id="171"
                                                            data-toggle="modal"
                                                            data-target="#add-payment_{{ $purchase->id }}"><i
                                                                class="fa fa-plus text-info"></i>&nbsp;&nbsp;{{ __('add_payment') }}</a>
                                                    @endif
                                                    <a href="#" class="get-payment dropdown-item" data-id="171"
                                                        data-toggle="modal"
                                                        data-target="#view-payment_{{ $purchase->id }}"><i
                                                            class="fa fa-eye text-primary"></i>&nbsp;&nbsp;{{ __('view_payment') }}</a>
                                                    <a id="delete" href="{{ route('purchase.destroy', $purchase->id) }}"
                                                        class="dropdown-item productDeleteBtn" data-action=""><i
                                                            class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                                                        {{ __('delete') }}</a>
                                                </div>
                                            </div>
                                            <div id="purchase-details_{{ $purchase->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true" class="modal fade text-left purchaseDetails">
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
                                                                        style="font-size: 15px;">{{ __('purchase_details') }}</i>
                                                                </div>
                                                                <div class="col-md-4 d-print-none">
                                                                    <button type="button" id="close-btn"
                                                                        data-dismiss="modal" aria-label="Close"
                                                                        class="close"><span aria-hidden="true"><i
                                                                                class="fa fa-times"></i></span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="purchase-content" class="modal-body">
                                                            <strong>{{ __('date') }}:
                                                            </strong>{{ dateFormat($purchase->date) }}<br><strong>{{ __('reference') }}:
                                                            </strong>{{ $purchase->reference_no }}
                                                            <br><br>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>{{ __('from') }}:</strong><br>{{ $purchase->warehouse->name }}<br>{{ $purchase->warehouse->phone }}<br>{{ $purchase->warehouse->address }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="float-right">
                                                                        <strong>{{ __('to') }}:</strong><br>{{ $purchase->supplier->name }}<br>{{ $purchase->supplier->email }}<br>{{ $purchase->supplier->phone }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <table class="table table-bordered product-purchase-list p-2">
                                                                <thead class="table-bg-color">
                                                                    <tr>
                                                                        <th>{{ __('sl') }}</th>
                                                                        <th>{{ __('product') }}</th>
                                                                        <th>{{ __('batch_no') }}</th>
                                                                        <th>{{ __('quantity') }}</th>
                                                                        <th>{{ __('purchase_cost') }}</th>
                                                                        <th>{{ __('tax') }} ({{ __('per_qty') }})</th>
                                                                        <th>{{ __('sub_total') }}</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($purchase->purchaseProducts as $productPurchase)
                                                                        <tr>
                                                                            <td><strong>{{ $loop->iteration }}</strong>
                                                                            </td>
                                                                            <td>{{ $productPurchase->product->name }}
                                                                                @if (feature('barcodes'))
                                                                                    [{{ $productPurchase->product->code }}]
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $purchase->purchaseBatches()->where('product_id', $productPurchase->product->id)->first()->name ?? 'N/A' }}
                                                                            </td>
                                                                            <td>{{ $productPurchase->qty }}</td>
                                                                            <td>{{ numberFormat($productPurchase->product->cost) }}
                                                                            </td>
                                                                            <td>{{ numberFormat($productPurchase->tax) }}
                                                                            </td>
                                                                            <td>{{ numberFormat($productPurchase->total) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td colspan="6" class="text-right">
                                                                            <strong>{{ __('total') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->total_cost) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('order_tax') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->order_tax) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('order_discount') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->order_discount) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('shipping_cost') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->shipping_cost) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('grand_total') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->grand_total) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('paid_amount') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($purchase->paid_amount) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <strong>{{ __('due') }}:</strong>
                                                                        </td>
                                                                        <td>{{ numberFormat($due_amount) }}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div class="row my-2">
                                                                <div class="col-12 border-1">
                                                                    <p><strong>{{ __('note') }}:</strong>
                                                                        {!! $purchase->note !!}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <strong>{{ __('created_by') }}:</strong><br>{{ $purchase->user->name }}<br>{{ $purchase->user->email }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Product Details Modal --}}
                                            {{-- Add Payment Modal --}}
                                            <div id="add-payment_{{ $purchase->id }}" data-backdrop="static"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header card-header-color">
                                                            <span id="exampleModalLabel"
                                                                class="modal-title list-title text-white">
                                                                {{ __('payment_deposit') }}</span>
                                                            <button type="button" data-dismiss="modal"
                                                                aria-label="Close" class="close"><span
                                                                    aria-hidden="true"><i
                                                                        class="fa fa-times text-white"></i></span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('purchase.add.payment', $purchase->id) }}"
                                                                method="POST" class="payment-form">
                                                                @csrf
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-hover table-bordered">
                                                                            <thead class="text-center">
                                                                                <tr>
                                                                                    <th colspan="3"
                                                                                        class="text-center">
                                                                                        {{ __('payment_summery') }}
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="2">
                                                                                        {{ __('grand_total') }}
                                                                                    </td>
                                                                                    <td>{{ $purchase->grand_total }}</td>

                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">
                                                                                        {{ __('paid') }}
                                                                                    </td>
                                                                                    <td>{{ $purchase->paid_amount }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">
                                                                                        {{ __('due') }}
                                                                                    </td>
                                                                                    <td>{{ $due_amount }}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-select name="payment_method" :required="false"
                                                                            title="{{ __('payment_method') }}"
                                                                            id="payment-method"
                                                                            placeholder="{{ __('select_a_option') }}">
                                                                            @foreach ($paymentMethods as $paymentMethod)
                                                                                <option
                                                                                    value="{{ $paymentMethod->value }}">
                                                                                    {{ $paymentMethod->value }} </option>
                                                                            @endforeach
                                                                        </x-select>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <x-input name="paid_amount"
                                                                            title="{{ __('amount') }}"
                                                                            placeholder="{{ __('enter_your_payable_amount') }}"
                                                                            :required="false" value=""></x-input>
                                                                    </div>

                                                                    <div class="col-md-12 mb-3 accountList">
                                                                        <div class="form-group">
                                                                            <label>{{ __('bank_account') }}</label>
                                                                            <select name="account_id"
                                                                                class="form-control mt-3">
                                                                                <option disabled selected>
                                                                                    {{ __('select_a_option') }}
                                                                                </option>
                                                                                @foreach ($accounts as $account)
                                                                                    <option value="{{ $account->id }}">
                                                                                        {{ $account->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label>{{ __('note') }}</label>
                                                                            <textarea rows="3" class="form-control" name="payment_note"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="purchase_id"
                                                                    value="{{ $purchase->id }}">
                                                                <button type="submit"
                                                                    class="btn common-btn">{{ __('submit') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- All Payment Modal --}}
                                            <div id="view-payment_{{ $purchase->id }}" data-backdrop="static"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header card-header-color">
                                                            <span id="exampleModalLabel"
                                                                class="modal-title list-title text-white">{{ __('payment_histroy') }}</span>
                                                            <button type="button" data-dismiss="modal"
                                                                aria-label="Close" class="close"><span
                                                                    aria-hidden="true"><i
                                                                        class="fa fa-times text-white"></i></span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-hover payment-list">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('date') }}</th>
                                                                        <th>{{ __('reference') }}</th>
                                                                        <th>{{ __('account') }}</th>
                                                                        <th>{{ __('amount') }}</th>
                                                                        <th>{{ __('paid_by') }}</th>
                                                                        <th>{{ __('action') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($purchase->payments as $payment)
                                                                        <tr>
                                                                            <td>{{ dateFormat($payment->created_at) }}
                                                                            </td>
                                                                            <td>{{ $payment->payment_reference }}</td>
                                                                            <td>{{ $payment->account->name ?? 'N?A' }}
                                                                            </td>
                                                                            <td>{{ $payment->amount }}</td>
                                                                            <td>
                                                                                {{ $payment->paying_method }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <a class="btn common-btn py-0 px-1"
                                                                                        href="#" role="button"
                                                                                        id="dropdownMenuLink"
                                                                                        data-toggle="dropdown"
                                                                                        aria-haspopup="true"
                                                                                        aria-expanded="false">
                                                                                        <i class="fa fa-ellipsis-h"></i>
                                                                                    </a>
                                                                                    <div class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuLink">
                                                                                        <a id="delete"
                                                                                            href="{{ route('purchase.delete.payment', $payment->id) }}"
                                                                                            class="dropdown-item"><i
                                                                                                class="fa fa-trash"></i>
                                                                                            {{ __('delete') }}</a>
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
        $('.accountList').hide(300);
        $('select[name="payment_method"]').on('change', function() {
            var value = $(this).val();
            if (value == 'Bank') {
                $('.accountList').show(300);
            } else {
                $('.accountList').hide(300);
            }
        });
    </script>
@endpush
