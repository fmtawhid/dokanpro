<div class="flex gap-2 flex-wrap mt-3">
    <div class="grow relative">
        <input type="radio" name="payment" value="cash" id="cash" class="peer sr-only payment_method" />
        <label for="cash"
            class="flex justify-center items-center gap-2 p-2 text-base font-semibold leading-relaxed text-teal-400 primary-bg-color-light h-12 border-2 border-transparent cursor-pointer payment_option"
            data-bg="primary-bg-color-light">
            <img src="{{ asset('/icons/cash.svg') }}" alt="" />
            <span>{{ __('cash') }}</span>
        </label>
    </div>

{{-- @foreach($paymentGateways as $key => $paymentGateway)
    @php
        $colorClasses = [
            'bg-slate-200 peer-checked:border-lime-500',
            'bg-red-100 peer-checked:border-lime-500',
            'bg-blue-100 peer-checked:border-lime-500',
            'primary-bg-color-light peer-checked:border-lime-500',
        ];
        $colorClass = $colorClasses[$key % count($colorClasses)];
    @endphp
    <div class="grow relative">
        <input type="radio" name="payment" id="card-{{ $key }}" value="{{ $paymentGateway->name }}" class="peer sr-only payment_method" />
        <label for="card-{{ $key }}"
            class="flex justify-center items-center gap-2 p-2 text-base font-semibold leading-relaxed h-12 border-2  cursor-pointer {{ $colorClass }}">
            <img src="{{ $paymentGateway->logo ?? asset('default/default.jpg') }}" alt="" style="width: 100px;" />
        </label>
    </div>
@endforeach --}}


@foreach($paymentGateways as $key => $paymentGateway)
@php
$colorClasses = [
    'bg-slate-100',
    'bg-red-100',
    'bg-blue-100',
    'primary-bg-color-light',
];
$colorClass = $colorClasses[$key % count($colorClasses)];
@endphp
<div class="grow relative">
    <input type="radio" name="payment" id="card-{{ $key }}" value="{{ $paymentGateway->name }}" class="peer sr-only payment_method" />
    <label for="card-{{ $key }}"
        class="flex justify-center items-center gap-2 p-2 text-base font-semibold leading-relaxed h-12 border-2 border-transparent cursor-pointer payment_option {{ $colorClass }}"
        data-bg="{{ $colorClass }}">
        <img src="{{ $paymentGateway->logo ?? asset('default/default.jpg') }}" alt="" style="width: 100px;" />
    </label>
</div>
@endforeach
</div>
