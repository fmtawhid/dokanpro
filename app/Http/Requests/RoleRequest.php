<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $description = 'nullable';
        $name = 'required';
        if (request()->isMethod('put')) {
            $description = 'required';
            $name = 'nullable';
        }

        return [
            'name' => $name . '|string|max:255|unique:roles,name,' . $this->role?->id,
            'description' => $description . '|string',
        ];
    }
}
