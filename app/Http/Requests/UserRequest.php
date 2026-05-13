<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $password = 'required|min:6';
        if (request()->isMethod('put')) {
            $password = 'nullable|min:6';
        }

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user?->id,
            'phone' => 'required|numeric',
            'password' => $password,
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
            'role_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
    }
}