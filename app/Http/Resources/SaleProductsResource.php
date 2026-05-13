<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        return [
            'id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->thumbnail?->file,
            'brand' => $product->brand?->title,
            'category' => $product->category?->name,
            'unit' => $product->unit?->name,
            'price' => (float) $product->price,
            'stock' => (int) $product->qty,
            'code' => $product->code,
            'tax' => (float) $this->tax,
            'sub_total' => (float) $this->total,
            'quantity' => (int) $this->qty,
        ];
    }
}
