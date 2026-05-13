<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'nullable|date',
            'product_ids' => 'required|array',
            'product_variant_ids' => 'nullable|array',
            'product_serial_numbers' => 'nullable|array',
            'qty' => 'required|array',
            'price' => 'nullable|array',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'tax_id' => 'nullable|integer',
            'coupon_id' => 'nullable|integer',
            'paid_amount' => 'required|numeric',
            'sale_note' => 'nullable|string',
            'staff_note' => 'nullable|string',
            'document' => 'nullable|file|mimes:png,jpg,jpeg,pdf',
        ];
    }
}
