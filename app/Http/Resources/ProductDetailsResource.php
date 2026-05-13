<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail?->file,
            'brand' => $this->brand?->title,
            'category' => $this->category?->name,
            'unit' => $this->unit?->name,
            'qty' => (int) $this->qty > 0 ? (int) $this->qty : 0,
            'alert_qty' => (int) $this->alert_quantity,
            'code' => $this->code,
            'product_type' => $this->type,
            'purchase_cost' => (float) $this->cost,
            'price' => (float) $this->price,
            'description' => $this->product_details
        ];
    }
}
