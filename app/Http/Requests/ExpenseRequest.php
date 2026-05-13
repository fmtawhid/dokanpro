<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'expense_category_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payment_type' => 'required|in:cash,bank',
            'account_id' => 'required_if:payment_type,bank|nullable|integer',
            'note' => 'nullable|string',
        ];
    }
}
