<div class="col-md-6 mb-3">
    <x-select name="customer_group_id" title="customer_group" :required="false" placeholder="select_a_option">
        @foreach ($customerGroups as $customerGroup)
            <option {{ isset($customer) && $customer->customer_group_id == $customerGroup->id ? 'selected' : '' }}
                value="{{ $customerGroup->id }}">{{ $customerGroup->name }} ({{ $customerGroup->percentage }}%)
            </option>
        @endforeach
    </x-select>
</div>
<div class="col-md-6 mb-3">
    <x-input name="name" title="name" type="text" :required="true" value="{{ $customer->name ?? '' }}"
        placeholder="enter_your_customer_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="company_name" title="company_name" type="text" :required="false"
        value="{{ $customer->company_name ?? '' }}" placeholder="enter_your_customer_company_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="email" title="email_address" type="email" :required="false"
        value="{{ $customer->email ?? '' }}" placeholder="enter_your_customer_email_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="phone_number" title="phone_number" type="text" :required="true"
        value="{{ $customer->phone_number ?? '' }}" placeholder="enter_your_customer_phone_number" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="tax_no" title="tax_number" type="text" :required="false"
        value="{{ $customer->tax_no ?? '' }}" placeholder="enter_your_customer_tax_number" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="address" title="address" type="text" :required="false" value="{{ $customer->address ?? '' }}"
        placeholder="enter_your_customer_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup title="password" type="password" name="password" value="" :required="true"
        placeholder="enter_your_employee_password" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="city" title="city" type="text" :required="false" value="{{ $customer->city ?? '' }}"
        placeholder="enter_your_customer_city" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="state" title="state" type="text" :required="false" value="{{ $customer->state ?? '' }}"
        placeholder="enter_your_customer_state" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="post_code" title="post_code" type="text" :required="false"
        value="{{ $customer->post_code ?? '' }}" placeholder="enter_your_customer_post_code" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup name="country" title="country" type="text" :required="false"
        value="{{ $customer->country ?? '' }}" placeholder="enter_your_customer_country" />
</div>
