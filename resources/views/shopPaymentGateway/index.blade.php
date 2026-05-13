@extends('layout.app')
@section('title', __('stripe_payment_gateway_configure'))
@section('content')
    <div class="container-fluid mb-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="m-0">{{ __('Payment Gateways') }}</h4>
        </div>
        <div class="row">
            @foreach ($paymentGateways as $paymentGateway)
                @php
                    $shopConfig                     = $shopWisePaymentGateway->firstWhere('name', $paymentGateway->name);
                    $configs                        = $shopConfig ? json_decode($shopConfig->config, true) : [];
                    $decodedPaymentGatewayConfig    = json_decode($paymentGateway->config, true) ?? [];
                    $mode                           = $shopConfig->mode ?? '';
                    $title                          = $shopConfig->title ?? '';
                    $logo                           = $shopConfig->logo ?? '';
                    $is_active                      = $shopConfig->is_active ?? '';
                @endphp
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                            <p class="paymentTitle m-0">
                                {{ strtoupper($paymentGateway->name) }}
                            </p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="{{ $is_active ? 'statusOn' : 'statusOff' }}">
                                    {{ $is_active ? 'On' : 'Off' }}
                                </span>
                                <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="{{ $is_active ? 'Turn off' : 'Turn on' }}">
                                    <a href="{{ route('shop-payment-gateway.toggle', $paymentGateway->id) }}"
                                        class="confirm">
                                        <input type="checkbox" {{ $is_active ? 'checked' : '' }} style="display:none;">
                                        <span class="slider round"></span>
                                    </a>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="py-2">
                                <img id="preview{{ $paymentGateway->name }}" class="paymentLogo"
                                    src="{{ $logo ? asset($logo) : asset($paymentGateway->logo) }}" alt="logo" loading="lazy"/>
                            </div>
                            <form action="{{ route('shop-payment-gateway.update', $paymentGateway->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="name" value="{{ $paymentGateway->name }}" />
                                <div class="mt-3">
                                    <x-select
                                        name="mode"
                                        :title="'Mode'"
                                        :id="'mode'"
                                        :placeholder="'Select Mode'"
                                        :required="false"
                                    >
                                        <option value="test" {{ $mode == 'test' ? 'selected' : '' }}>
                                            Test
                                        </option>
                                        <option value="live" {{ $mode == 'live' ? 'selected' : '' }} {{ app()->environment('local') ? 'disabled' : '' }}>
                                            Live
                                        </option>
                                    </x-select>
                                </div>
                                @php
                                    $shopConfig = $shopWisePaymentGateway->firstWhere('name', $paymentGateway->name);
                                    $configs = $shopConfig ? json_decode($shopConfig->config, true) : [];
                                @endphp

                                @foreach ($decodedPaymentGatewayConfig as $key => $defaultValue)
                                    @php
                                        $label = ucwords(str_replace('_', ' ', $key));
                                        $inputValue = isset($configs[$key]) ? $configs[$key] : '';
                                    @endphp
                                    <div class="mt-3">
                                        <x-input
                                            :value="$inputValue"
                                            name="config[{{ $key }}]"
                                            type="text"
                                            placeholder="{{ $label }}"
                                            title="{{ $label }}"
                                            :required="true"
                                        />
                                    </div>
                                @endforeach
                                <div class="mt-3">
                                    <x-input
                                        name="title"
                                        type="text"
                                        title="Payment Gateway Title"
                                        :value="$title"
                                        :required="true"
                                    />
                                </div>
                                <div class="mt-3">
                                    <x-file
                                        name="logo"
                                        title="Choose Logo"
                                        preview="preview{{ $paymentGateway->name }}"
                                        :required="false"
                                    />
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <x-common-button name="save_and_update" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
