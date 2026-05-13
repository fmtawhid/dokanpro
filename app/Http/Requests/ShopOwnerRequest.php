<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopOwnerRequest extends FormRequest
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
        $method = request()->isMethod('put');
        $isRequired = 'required';
        if ($method) {
            $isRequired = 'nullable';
        }
        return [
            'name' =>  $isRequired . '|string|max:255',
            'email' => $isRequired . '|email|unique:users,email',
            'password' => $isRequired . '|min:6|max:16',
            'shop_name' => 'required|string|max:255',
            'shop_category_id' => 'required|integer',
            'shop_logo' => 'nullable|file|mimes:png,jpg,jpeg',
            'shop_favicon' => 'nullable|file|mimes:png,jpg,jpeg',
        ];
    }
}
