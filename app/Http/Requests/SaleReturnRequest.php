<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnRequest extends FormRequest
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
            'products' => "nullable|array",
            'total_discount' => "required|numeric",
            'total_tax' => "required|numeric",
            'item' => "required|numeric",
            'total_price' => "required|numeric",
            'order_tax' => "required|numeric",
            'grand_total' => "required|numeric",
            'note' => "nullable|string",
        ];
    }
}
