<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tax = $this->tax->rate ?? 0;
        if ($tax > 0) {
            $tax = $this->price * $this->tax->rate / 100;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'qty' => $this->qty,
            'ending_date' => $this->ending_date ?? '',
            'price' => $this->price,
            'cost' => $this->cost,
            'tax' => $tax,
            'subtotal' => $this->price + $tax
        ];
    }
}
