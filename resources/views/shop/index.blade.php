@extends('layout.app')
@section('title', __('shops'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <x-section-header title="shops" />
                <a href="{{ route('shop.create') }}" class="btn common-btn">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_shop') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable table-responsive">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('shop_category') }}</th>
                                <th>{{ __('shop_owner') }}</th>
                                <th>{{ __('shop_owner_email') }}</th>
                                <th>{{ __('shop_owner_phone_number') }}</th>
                                <th>{{ __('subscription') }}</th>
                                <th>{{ __('subscription_expire') }}</th>
                                <th>{{ __('lifetime') }}</th>
                                <th>{{ __('status') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shops as $shop)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $shop->name ?? 'N/A' }}</td>
                                    <td>{{ $shop->shopCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $shop->user->name ?? 'N/A' }}</td>
                                    <td>{{ $shop->user->email ?? 'N/A' }}</td>
                                    <td>{{ $shop->user->phone ?? 'N/A' }}</td>
                                    <td>{{ $shop->currentSubscriptions()->subscription->title ?? 'N/A' }}</td>
                                    <td>{{ $shop->currentSubscriptions()->expired_at ?? 'N/A' }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="shopLifetime d-none"
                                                data-action="{{ route('shop.life.time.expire.chanage', $shop->id) }}"
                                                {{ $shop->is_lifetime ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="shopStatus d-none" data-id="{{ $shop->id }}"
                                                {{ $shop->status->value == 'Active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-custom-width">
                                            <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="">
                                                <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                                    data-target="#editShop_{{ $shop->id }}"><i
                                                        class="fa fa-edit text-info"></i>&nbsp;&nbsp;
                                                    {{ __('edit') }}</a>
                                                <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                                    data-target="#shopOwnerPasswordReset_{{ $shop->id }}"><i
                                                        class="fa fa-eye text-primary"></i>&nbsp;&nbsp;
                                                    {{ __('reset_password') }}</a>
                                                <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                                    data-target="#shopSubscriptionChange_{{ $shop->id }}"><i
                                                        class="fa fa-ticket text-success"></i>&nbsp;&nbsp;
                                                    {{ __('subscription_change') }}</a>
                                            </div>
                                        </div>
                                        <!-- Shop Edit Modal Start -->
                                        <div id="editShop_{{ $shop->id }}" tabindex="-1" data-backdrop="static"
                                            role="dialog" aria-labelledby="editShopLabel_{{ $shop->id }}""
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('shop.update', $shop->id) }}" method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <x-modal-header header="edit_shop"
                                                            id="editShopLabel_{{ $shop->id }}" />
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <x-inputGroup name="shop_name" title="name"
                                                                        type="text" :required="true"
                                                                        value="{{ $shop->name }}"
                                                                        placeholder="enter_your_shop_name" />
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <x-select name="shop_category_id" title="shop_category"
                                                                        placeholder="select_a_option" :required="false">
                                                                        @foreach ($shopCategories as $shopCategory)
                                                                            <option value="{{ $shopCategory->id }}"
                                                                                {{ $shop->shop_category_id == $shopCategory->id ? 'selected' : '' }}>
                                                                                {{ $shopCategory->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </x-select>
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
                                        <!-- Shop Edit Modal End -->
                                        <!-- Shop Owner Password Reset Modal Start -->
                                        <div id="shopOwnerPasswordReset_{{ $shop->id }}" tabindex="-1"
                                            data-backdrop="static" role="dialog"
                                            aria-labelledby="shopOwnerPasswordResetLabel_{{ $shop->id }}"
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('shop.owner.reset.password', $shop->user->id) }}"
                                                        method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <x-modal-header header="shop_owner_password_reset"
                                                            id="shopOwnerPasswordResetLabel_{{ $shop->id }}" />
                                                        <div class="modal-body">
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="password" title="password"
                                                                    type="password" :required="true" value=""
                                                                    placeholder="enter_your_shop_owner_password" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="password_confirmation"
                                                                    title="password_confirmation" type="password"
                                                                    :required="true" value=""
                                                                    placeholder="enter_your_shop_owner_password_confirmation" />
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
                                        <!-- Shop Owner Password Reset Modal End -->
                                        <!-- Shop Subscription Change Modal Start -->
                                        <div id="shopSubscriptionChange_{{ $shop->id }}" tabindex="-1"
                                            data-backdrop="static" role="dialog"
                                            aria-labelledby="changeSubscriptionModalLabel_{{ $shop->id }}"
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('shop.subscription.chanage', $shop->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <x-modal-header header="subscription_change"
                                                            id="changeSubscriptionModalLabel_{{ $shop->id }}" />
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <x-select name="subscription_id" title="subscriptions"
                                                                        placeholder="select_a_option" :required="true">
                                                                        @foreach ($subsciptions as $subsciption)
                                                                            <option value="{{ $subsciption->id }}"
                                                                                {{ isset($shop->currentSubscriptions()->subscription) && $shop->currentSubscriptions()->subscription->id == $subsciption->id ? 'selected' : '' }}>
                                                                                {{ $subsciption->title }}
                                                                                {{ isset($shop->currentSubscriptions()->subscription) && $shop->currentSubscriptions()->subscription->id == $subsciption->id ? '(Current)' : '' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </x-select>
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
                                        <!-- Shop Subscription Change Modal End -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('.shopStatus').on("change", function() {
            const id = $(this).attr('data-id')
            const url = "{{ url('shop/status-chanage/') }}";
            if ($(this).is(":checked")) {
                window.location.href = url + '/' + id + '/Active';
            } else {
                window.location.href = url + '/' + id + '/Inactive';
            }
        });
        $('.shopLifetime').on("change", function() {
            const action = $(this).attr('data-action');
            new swal({
                title: "Are you sure?",
                text: "Desire to unlock all features permanently at no cost.",
                type: "warning",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#29aae1",
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.value) {
                    window.location.href = action;
                }
            });
        });
    </script>
@endpush
