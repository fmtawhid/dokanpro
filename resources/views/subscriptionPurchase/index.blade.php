@extends('layout.app')
@section('title', __('subscription_purchase'))
@section('content')
    <section>
        <div class="subscription-container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="subscription_purchase" />
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($subscriptions as $subscription)
                            <div class="col-md-4 mb-3">
                                <div class="subscription-container">
                                    <h2 class="title">{{ $subscription->title }}</h2>
                                    <h3 class="price">
                                        {{ numberFormat($subscription->price) }}<span>/{{ $subscription->recurring_type }}</span>
                                    </h3>
                                    <b class="offer">{{ __('you_can_create') }} {{ $subscription->shop_limit }}
                                        {{ __('branche_and_also_create') }}
                                        {{ $subscription->product_limit }} {{ __('products_for_a_branch') }}.</b>
                                    <p class="description">{{ $subscription->description }}.</p>
                                    <button type="button"
                                        data-action="{{ route('subscription.purchase.update', $subscription->id) }}"
                                        class="subscribe-button">{{ __('subscribe_now') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('.subscribe-button').on("click", function() {
            const action = $(this).attr('data-action');
            new swal({
                title: "Are you sure?",
                text: "To purchase this subscription",
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
