<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $isRequired = 'required';
        if (request()->isMethod('put')) {
            $isRequired = 'nullable';
        }

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->store?->user->id,
            'password' => $isRequired . '|min:6|max:191',
            'store_name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'store_email' => 'required|email',
            'phone_number' => 'nullable|string|max:16',
            'address' => 'required|string|max:191',
            'country' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'postal_code' => 'nullable|string|max:191',
            'status' => $isRequired . '|in:Active,Inactive',
        ];
    }
}
