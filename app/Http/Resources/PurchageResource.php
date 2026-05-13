<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchageResource extends JsonResource
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
            'supplier' => $this->supplier?->name,
            'grand_total' => (float) $this->grand_total,
            'paid_amount' => (float) $this->paid_amount,
            'due_amount' => (float) $this->grand_total - $this->paid_amount,
            'status' => $this->payment_status ? 'Paid' : 'Due',
        ];
    }
}
