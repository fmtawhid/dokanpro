<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'customer_group_id' => 'nullable|integer',
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|unique:customers,email,' . $this->customer?->id,
            'phone_number' => 'required|string|max:16',
            'address' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'company_name' => 'nullable|string|max:191',
            'tax_no' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'post_code' => 'nullable|string|max:191',
            'password' => 'nullable|min:6|max:16',
        ];
    }
}
