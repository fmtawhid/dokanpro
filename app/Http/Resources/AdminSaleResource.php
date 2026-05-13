<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSaleResource extends JsonResource
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
            'date' => Carbon::parse($this->date)->format('d M, Y'),
            'reference_no' => $this->reference_no,
            'biller' => $this->user?->name,
            'qty' => (int) $this->total_qty,
            'discount' => (float) $this->total_discount,
            'tax' => (float) $this->total_tax,
            'total_price' => (float) $this->total_price,
            'payment_method' => $this->payment_method,
            'grand_total' => (float) $this->grand_total
        ];
    }
}
