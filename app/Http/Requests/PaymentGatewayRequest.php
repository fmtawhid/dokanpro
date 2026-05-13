<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'name' => 'nullable',
            'mode' => 'required',
            'config' => 'required|array',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }

    public function messages(): array
    {
        $request = request();
        if ($request->is('api/*')) {
            $lan = $request->header('accept-language') ?? 'en';
            app()->setLocale($lan);
        }

        return [
            'title.required' => __('The title field is required.'),
            'logo.image' => __('The logo must be an image.'),
            'logo.mimes' => __('The logo must be a file of type: png, jpg, jpeg, png, svg, webp.'),
        ];
    }
}
