<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SigninRequest extends FormRequest
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
            'email' => 'required|exists:users,email',
            'password' => 'required|min:6'
        ];
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function generateCredential()
    {
        return [
            ($this->isContactEmail() ? 'email' : 'phone') => $this->getContact(),
            'password' => $this->get('password')
        ];
    }

    public function isContactEmail(): bool
    {
        if (empty($this->getContact())) return false;

        return filter_var($this->getContact(), FILTER_VALIDATE_EMAIL);
    }
}
