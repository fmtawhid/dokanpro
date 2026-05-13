<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="name" title="name" :required="true" value="{{ $supplier->name ?? '' }}"
        placeholder="enter_your_supplier_name" />
</div>
<div class="col-md-6 mb-3">
    <x-fileInputGroup name="image" title="image" :required="false" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="email" name="email" title="email_address" :required="true"
        value="{{ $supplier->email ?? '' }}" placeholder="enter_your_supplier_email_address" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="phone_number" title="phone_number" :required="true"
        value="{{ $supplier->phone_number ?? '' }}" placeholder="enter_your_supplier_phone_number" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="company_name" title="company_name" :required="true"
        value="{{ $supplier->company_name ?? '' }}" placeholder="enter_your_supplier_company_name" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="vat_number" title="tax_number" :required="false"
        value="{{ $supplier->vat_number ?? '' }}" placeholder="enter_your_supplier_tax_number" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="city" title="city" :required="true" value="{{ $supplier->city ?? '' }}"
        placeholder="enter_your_supplier_city" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="state" title="state" :required="false" value="{{ $supplier->state ?? '' }}"
        placeholder="enter_your_supplier_state" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="postal_code" title="post_code" :required="false"
        value="{{ $supplier->postal_code ?? '' }}" placeholder="enter_your_supplier_post_code" />
</div>
<div class="col-md-6 mb-3">
    <x-inputGroup type="text" name="country" title="country" :required="false" value="{{ $supplier->country ?? '' }}"
        placeholder="enter_your_supplier_country" />
</div>
<div class="col-md-12 mb-3">
    <x-inputGroup type="text" name="address" title="address" :required="true"
        value="{{ $supplier->address ?? '' }}" placeholder="enter_your_supplier_address" />
</div>
