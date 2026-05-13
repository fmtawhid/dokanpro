<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoneyTransferResource extends JsonResource
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
            'date' => $this->created_at->format('d M, Y'),
            'reference_no' => $this->reference_no,
            'from' => $this->fromAccount->name,
            'to' => $this->toAccount->name,
            'amount' => (float) $this->amount
        ];
    }
}
