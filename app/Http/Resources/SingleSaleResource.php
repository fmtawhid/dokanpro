<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleSaleResource extends JsonResource
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
            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
                'phone' => $this->customer?->phone_number,
            ],
            'products' => SaleProductsResource::collection($this->productSales),
            'total_product' => (int) $this->item,
            'discount' => (float) $this->total_discount,
            'tax' => (float) $this->total_tax,
            'total_price' => (float) $this->total_price,
            'payment_method' => $this->payment_method,
            'grand_total' => (float) $this->grand_total,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'time' => Carbon::parse($this->created_at)->format('h:i A'),
        ];
    }
}
