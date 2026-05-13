<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|between:0,9999999.99',
            'shop_limit' => 'required|integer|between:0,9999999',
            'product_limit' => 'required|integer|between:0,9999999',
            'recurring_type' => 'required|in:Onetime,Weekly,Monthly,Yearly',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
