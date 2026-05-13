<div class="col-md-6 mb-3">
    <x-inputGroup name="store_name" title="store_name" type="text" :required="true" value="{{ $store->name ?? '' }}"
        placeholder="enter_your_store_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="store_email" title="store_email_address" type="email" :required="true"
        value="{{ $store->email ?? '' }}" placeholder="enter_your_store_email_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="phone_number" title="store_phone_number" type="text" :required="false"
        value="{{ $store->phone_number ?? '' }}" placeholder="enter_your_store_phone_number" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="address" title="store_address" type="text" :required="false"
        value="{{ $store->address ?? '' }}" placeholder="enter_your_store_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="postal_code" title="store_postal_code" type="text" :required="false"
        value="{{ $store->postal_code ?? '' }}" placeholder="enter_your_store_postal_code" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="city" title="store_city" type="text" :required="false" value="{{ $store->city ?? '' }}"
        placeholder="enter_your_store_city" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="state" title="store_state" type="text" :required="false" value="{{ $store->state ?? '' }}"
        placeholder="enter_your_store_state" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="country" title="store_country" type="text" :required="false"
        value="{{ $store->country ?? '' }}" placeholder="enter_your_store_country" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="name" title="store_manager_name" type="text" :required="true"
        value="{{ $store->user->name ?? '' }}" placeholder="enter_your_store_manager_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="email" title="store_manager_email" type="email" :required="true"
        value="{{ $store->user->email ?? '' }}" placeholder="enter_your_store_manager_email" />
</div>
@if (!isset($store))
    <div class="col-md-6 mb-3">
        <x-inputGroup name="password" title="store_manager_password" type="password" :required="true" value=""
            placeholder="enter_your_store_manager_password" />
    </div>
@endif
@isset($statuses)
    <div class="col-md-6">
        <x-select name="status" title="{{ __('status') }}" placeholder="{{ __('select_a_option') }}">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">
                    {{ $status->value }}
                </option>
            @endforeach
        </x-select>
    </div>
@endisset
<div class="col-md-12">
    <x-textarea-group name="description" title="store_description" :required="true"
        value="{{ $store->description ?? '' }}" placeholder="enter_your_store_description" />
</div>
