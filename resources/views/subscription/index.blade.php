@extends('layout.app')
@section('title', __('subscriptions'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="subscriptions" />
                    <x-create-button name="add_subscription" target="createSubscriptionModal" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('title') }}</th>
                                    <th>{{ __('price') }}</th>
                                    <th>{{ __('shop_limit') }}</th>
                                    <th>{{ __('product_limit') }}</th>
                                    <th>{{ __('recurring_type') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subscription->title }}</td>
                                        <td>{{ numberFormat($subscription->price) }}</td>
                                        <td>{{ $subscription->shop_limit }}</td>
                                        <td>{{ $subscription->product_limit }}</td>
                                        <td>{{ $subscription->recurring_type }}</td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="subscriptionStatus d-none"
                                                    data-id="{{ $subscription->id }}"
                                                    {{ $subscription->status->value == 'Active' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                                        data-target="#editSubscriptionModal_{{ $subscription->id }}"><i
                                                            class="fa fa-edit text-info"></i>
                                                        {{ __('edit') }}</a>
                                                </div>
                                            </div>

                                            <div id="editSubscriptionModal_{{ $subscription->id }}" tabindex="-1"
                                                data-backdrop="static" role="dialog"
                                                aria-labelledby="editSubscriptionModalLabel_{{ $subscription->id }}" aria-hidden="true"
                                                class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('subscription.update', $subscription->id) }}"
                                                            method="POST">
                                                            @method('put')
                                                            @csrf
                                                            <x-modal-header header="edit_subscription"
                                                                id="editSubscriptionModalLabel_{{ $subscription->id }}" />
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-input name="title" title="title"
                                                                            type="text" :required="true"
                                                                            value="{{ $subscription->title }}"
                                                                            placeholder="enter_your_subscription_title" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-input name="price" title="price"
                                                                            type="number" :required="true"
                                                                            value="{{ $subscription->price }}"
                                                                            placeholder="enter_your_subscription_price" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-input name="shop_limit" title="shop_limit"
                                                                            type="number" :required="true"
                                                                            value="{{ $subscription->shop_limit }}"
                                                                            placeholder="enter_your_subscription_shop_limit" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-input name="product_limit" title="product_limit"
                                                                            type="number" :required="true"
                                                                            value="{{ $subscription->product_limit }}"
                                                                            placeholder="enter_your_subscription_product_limit" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-select name="recurring_type"
                                                                            title="recurring_type"
                                                                            placeholder="select_a_option">
                                                                            @foreach ($recurringTypes as $recurringType)
                                                                                <option
                                                                                    {{ $subscription->recurring_type->value == $recurringType->value ? 'selected' : '' }}
                                                                                    value="{{ $recurringType->value }}">
                                                                                    {{ $recurringType->value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </x-select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <x-select name="status" title="status"
                                                                            placeholder="select_a_option">
                                                                            @foreach ($statuses as $status)
                                                                                <option
                                                                                    {{ $subscription->status->value == $status->value ? 'selected' : '' }}
                                                                                    value="{{ $status->value }}">
                                                                                    {{ $status->value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </x-select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <x-textarea-group name="description"
                                                                            title="description" :required="true"
                                                                            placeholder="enter_your_subscription_description"
                                                                            value="{{ $subscription->description }}" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label">{{ __('features') }}</label>
                                                                        <div class="row">
                                                                            @foreach ($features as $feature)
                                                                                <div class="col-md-6 mb-3">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="checkbox" name="features[]"
                                                                                            value="{{ $feature->id }}" id="feature_edit_{{ $subscription->id }}_{{ $feature->id }}"
                                                                                            {{ $subscription->features->contains($feature->id) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label" for="feature_edit_{{ $subscription->id }}_{{ $feature->id }}">
                                                                                            {{ $feature->name }}
                                                                                        </label>
                                                                                    </div>
                                                                                    <input type="number" name="feature_price_{{ $feature->id }}"
                                                                                        class="form-control form-control-sm mt-1" placeholder="Price (tk)"
                                                                                        value="{{ $subscription->features->find($feature->id)?->pivot->price ?? 0 }}" />
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <x-modal-close-button />
                                                                <x-common-button name="update_and_save" />
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

    <div id="createSubscriptionModal" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="createSubscriptionModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('subscription.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_subscription" id="createSubscriptionModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input name="title" title="title" type="text" :required="true"
                                    placeholder="enter_your_subscription_title" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input name="price" title="price" type="number" :required="true"
                                    placeholder="enter_your_subscription_price" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input name="shop_limit" title="shop_limit" type="number" :required="true"
                                    placeholder="enter_your_subscription_shop_limit" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input name="product_limit" title="product_limit" type="number" :required="true"
                                    placeholder="enter_your_subscription_product_limit" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select name="recurring_type" title="recurring_type" placeholder="select_a_option">
                                    @foreach ($recurringTypes as $recurringType)
                                        <option value="{{ $recurringType->value }}" {{ old('recurring_type') == $recurringType->value ? 'selected' : '' }}>
                                            {{ $recurringType->value }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select name="status" title="status" placeholder="select_a_option">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-12">
                                <x-textarea-group name="description" title="description" :required="true"
                                    placeholder="enter_your_subscription_description" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ __('features') }}</label>
                                <div class="row">
                                    @foreach ($features as $feature)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="features[]"
                                                    value="{{ $feature->id }}" id="feature_create_{{ $feature->id }}"
                                                    {{ old('features') && in_array($feature->id, old('features')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="feature_create_{{ $feature->id }}">
                                                    {{ $feature->name }}
                                                </label>
                                            </div>
                                            <input type="number" name="feature_price_{{ $feature->id }}"
                                                class="form-control form-control-sm mt-1" placeholder="Price (tk)"
                                                value="{{ old('feature_price_' . $feature->id, 0) }}" />
                                        </div>
                                    @endforeach
                                </div>
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
        $('.subscriptionStatus').on("change", function() {
            const id = $(this).attr('data-id')
            const url = "{{ url('subscription/status-chanage/') }}";
            if ($(this).is(":checked")) {
                window.location.href = url + '/' + id + '/Active';
            } else {
                window.location.href = url + '/' + id + '/Inactive';
            }
        });
    </script>
@endpush
