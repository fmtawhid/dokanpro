<?php

namespace App\Http\Requests;

use App\Repositories\GeneralSettingRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $unit_id = 'required|integer';
        $sale_unit_id = 'required|integer';
        $purchase_unit_id = 'required|integer';
        $price = 'required|numeric|max:9999999';
        $cost = 'required|numeric|max:9999999';

        if ($this->type == 'combo') {
            $unit_id = 'nullable|integer';
            $sale_unit_id = 'nullable|integer';
            $purchase_unit_id = 'nullable|integer';
            $price = 'nullable|numeric|max:9999999';
            $cost = 'nullable|numeric|max:9999999';
        }
        $mainShop = mainShop();
        if ($mainShop) {
            $generalSettings = GeneralSettingRepository::query()->where('shop_id', $mainShop->id)->first();
        }
        $digits = $generalSettings->barcode_digits ?? 8;
        $isCodeRequired = feature('barcodes') ? 'required' : 'nullable';
        if ($this->type == 'digital') {
            $price = 'nullable|numeric|max:9999999';
            $cost = 'nullable|numeric|max:9999999';
        }
        return [
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'code' => [
                $isCodeRequired,
                'numeric',
                'digits_between:' . $digits . ',' . $digits,
                Rule::unique('products')->where(function ($query) {
                    $query->where('shop_id', mainShop()->id);
                })->ignore($this->product?->id),
            ],
            'price' => $price,
            'cost' => $cost,
            'barcode_symbology' => 'nullable|string|max:255',
            'brand_id' => 'required|integer',
            'category_id' => 'required|integer',
            'unit_id' => $unit_id,
            'sale_unit_id' => $sale_unit_id,
            'purchase_unit_id' => $purchase_unit_id,
            'alert_quantity' => 'nullable|integer',
            'is_featured' => 'nullable',
            'product_details' => 'nullable|string',
            'variant_name' => 'nullable|array',
            'item_code' => 'nullable|array',
            'additional_price' => 'nullable|array',
        ];
    }
}
