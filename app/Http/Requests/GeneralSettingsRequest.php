<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
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
            'site_title' => 'required|string|max:255',
            'site_logo' => 'nullable|file|mimes:png,jpg,jpeg',
            'small_logo' => 'nullable|file|mimes:png,jpg,jpeg',
            'favicon' => 'nullable|file|mimes:png,jpg,jpeg',
            'currency_id' => 'required|integer',
            'currency_position' => 'required|string',
            'date_with_time' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'developed_by' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'barcode_digits' => 'nullable|integer',
            'dark_mode' => 'required|boolean',
        ];
    }
}
