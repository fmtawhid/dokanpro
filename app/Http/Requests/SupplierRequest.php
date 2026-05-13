<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
            'company_name' => 'required|string|max:255|unique:suppliers,company_name,' . $this->supplier?->id,
            'email' => 'required|email|unique:suppliers,email,' . $this->supplier?->id,
            'phone_number' => 'required|string|max:16|unique:suppliers,phone_number,' . $this->supplier?->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:255|unique:suppliers,vat_number,' . $this->supplier?->id,
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ];
    }
}
