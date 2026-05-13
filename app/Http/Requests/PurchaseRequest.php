<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'warehouse_id' => "required|integer",
            'supplier_id' => "required|integer",
            'tax_id' => "nullable|integer",
            'document' => "nullable|mimes:jpg,jpeg,png",
            'products' => "required|array",
            'payment_method' => 'required|in:Cash,Bank',
            'paid_amount' => 'required|numeric',
            'total_qty' => 'required|numeric|min:1',
        ];

        if (request()->isMethod('put') || !$this->paid_amount) {
            $rules['payment_method'] = 'nullable';
            $rules['paid_amount'] = 'nullable|numeric';
        }
        if ($this->payment_method == 'Bank') {
            $rules['account_id'] = 'required|exists:accounts,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Pleace Select Products',
        ];
    }
}
