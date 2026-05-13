<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailSettingRequest extends FormRequest
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
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'email_from' => 'required|email',
            'from_name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'encryption' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ];
    }
}
