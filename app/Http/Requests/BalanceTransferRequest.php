<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BalanceTransferRequest extends FormRequest
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
        $isAccountId = 'nullable';
        if ($this->payment_method == 'Bank') {
            $isAccountId = 'required';
        }

        return [
            'amount' => 'required|integer',
            'payment_method' => 'required|string',
            'purpose' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'account_id' => $isAccountId . '|integer'
        ];
    }
}
