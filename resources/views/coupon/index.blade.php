@extends('layout.app')
@section('title', __('coupons'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="coupons" />
                    <x-create-button target="createCouponModal" name="add_coupon" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported"></th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('code') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('minimum_amount') }}</th>
                                    <th>{{ __('maximum_amount') }}</th>
                                    <th>{{ __('quantity') }}</th>
                                    <th>{{ __('available') }}</th>
                                    <th>{{ __('expired_at') }}</th>
                                    <th>{{ __('created_by') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        @if ($coupon->type->value == 'Percentage')
                                            <td>
                                                <div class="badge badge-primary">{{ $coupon->type->value }}</div>
                                            </td>
                                        @else
                                            <td>
                                                <div class="badge badge-info">{{ $coupon->type->value }}</div>
                                            </td>
                                        @endif
                                        <td>{{ numberFormat($coupon->amount) }}</td>
                                        <td>{{ numberFormat($coupon->min_amount) ?? 'N/A' }}</td>
                                        <td>{{ numberFormat($coupon->max_amount) ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="badge badge-info">{{ $coupon->qty }}</div>
                                        </td>
                                        @if ($coupon->qty > $coupon->used)
                                            <td class="text-center">
                                                <div class="badge badge-danger">{{ $coupon->qty - $coupon->used }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <div class="badge badge-danger">0</div>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="badge badge-success">
                                                {{ dateFormat($coupon->expired_date) }}
                                            </div>
                                        </td>
                                        <td>{{ $coupon->user->name }}</td>
                                        <td>
                                            <x-edit-button target="editCouponModal_{{ $coupon->id }}" />
                                            <x-delete-button route="{{ route('coupon.destroy', $coupon->id) }}" />

                                            <!-- Edit Modal -->
                                            <div id="editCouponModal_{{ $coupon->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static"
                                                aria-labelledby="editCouponModalLabel_{{ $coupon->id }}"
                                                aria-hidden="true" class="modal fade text-left editCouponModal">
                                                <div role="document" class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <x-modal-header header="edit_coupon"
                                                            id="editCouponModalLabel_{{ $coupon->id }}" />
                                                        <form action="{{ route('coupon.update', $coupon->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-inputGroup name="name" title="name"
                                                                            type="text" :required="true"
                                                                            value="{{ $coupon->name }}"
                                                                            placeholder="enter_your_coupon_name" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-inputGroup name="code" title="code"
                                                                            type="text" :required="true"
                                                                            value="{{ $coupon->code }}"
                                                                            placeholder="enter_your_coupon_code" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-select name="type" title="type"
                                                                            placeholder="select_a_option" :required="true">
                                                                            @foreach ($couponTypes as $couponType)
                                                                                <option
                                                                                    {{ $coupon->type->value == $couponType->value ? 'selected' : '' }}
                                                                                    value="{{ $couponType->value }}">
                                                                                    {{ $couponType->value }}</option>
                                                                            @endforeach
                                                                        </x-select>
                                                                    </div>
                                                                    <div class="col-md-6 minimum-amount mb-3">
                                                                        <x-inputGroup name="minimum_amount"
                                                                            title="minimum_amount" type="number"
                                                                            :required="true"
                                                                            value="{{ $coupon->min_amount }}"
                                                                            placeholder="enter_your_minimum_purchase_amount" />
                                                                    </div>
                                                                    <div class="col-md-6 minimum-amount mb-3">
                                                                        <x-inputGroup name="maximum_amount"
                                                                            title="maximum_amount" type="number"
                                                                            :required="true"
                                                                            value="{{ $coupon->max_amount }}"
                                                                            placeholder="enter_your_maximum_purchase_amount" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-inputGroup name="amount" title="amount"
                                                                            type="number" :required="true"
                                                                            value="{{ $coupon->amount }}"
                                                                            placeholder="enter_your_discount_amount" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-inputGroup name="quantity" title="quantity"
                                                                            type="number" :required="true"
                                                                            value="{{ $coupon->qty }}"
                                                                            placeholder="enter_your_coupon_apply_quantity" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-inputGroup name="expired_date"
                                                                            title="expired_date" type="date"
                                                                            :required="true"
                                                                            value="{{ Carbon\Carbon::parse($coupon->expired_at)->format('Y-m-d') }}"
                                                                            placeholder="enter_your_coupon_expired_date" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <x-modal-close-button />
                                                                <x-common-button name="submit" />
                                                            </div>
                                                        </form>
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
    <!-- Create Modal -->
    <div id="createCouponModal" tabindex="-1" role="dialog" aria-labelledby="createCouponModalLabel" aria-hidden="true"
        data-backdrop="static" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <x-modal-header header="new_coupon" id="createCouponModalLabel" />
                <form action="{{ route('coupon.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="name" title="name" type="text" :required="true"
                                    value="" placeholder="enter_your_coupon_name" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="code" title="code" type="text" :required="true"
                                    value="" placeholder="enter_your_coupon_code" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select name="type" title="type" placeholder="select_a_option" :required="true">
                                    @foreach ($couponTypes as $couponType)
                                        <option {{ old('type') == $couponType->value ? 'selected' : '' }}
                                            value="{{ $couponType->value }}">{{ $couponType->value }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-6 minimum-amount mb-3">
                                <x-inputGroup name="minimum_amount" title="minimum_amount" type="number"
                                    :required="true" value="" placeholder="enter_your_minimum_purchase_amount" />
                            </div>
                            <div class="col-md-6 minimum-amount mb-3">
                                <x-inputGroup name="maximum_amount" title="maximum_amount" type="number"
                                    :required="true" value="" placeholder="enter_your_maximum_purchase_amount" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="amount" title="amount" type="number" :required="true"
                                    value="" placeholder="enter_your_discount_amount" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="quantity" title="quantity" type="number" :required="true"
                                    value="" placeholder="enter_your_coupon_apply_quantity" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="expired_date" title="expired_date" type="date" :required="true"
                                    value="" placeholder="enter_your_coupon_expired_date" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-modal-close-button />
                        <x-common-button name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(".minimum-amount").hide();
        $(document).on("change", "select[name='type']", function() {
            if ($(this).val() == 'Amount') {
                $("#createCouponModal .minimum-amount").show();
                $("#createCouponModal .minimum-amount").prop('required', true);
            } else {
                $("#createCouponModal .minimum-amount").hide();
                $("#createCouponModal .minimum-amount").prop('required', false);
                $("#createCouponModal .icon-text").text('%');
            }
        });
        $(document).on("change", ".editCouponModal select[name='type']", function() {
            if ($(this).val() == 'Amount') {
                $(".editCouponModal .minimum-amount").show();
                $(".editCouponModal .minimum-amount").prop('required', true);
            } else {
                $(".editCouponModal .minimum-amount").hide();
                $(".editCouponModal .minimum-amount").prop('required', false);
                $(".editCouponModal .icon-text").text('%');
            }
        });
    </script>
@endpush
