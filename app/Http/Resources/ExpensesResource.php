<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesResource extends JsonResource
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
            'wearehouse' => $this->warehouse?->name,
            'category' => $this->expenseCategory?->name,
            'amount' => (float) $this->amount,
            'note' => $this->note
        ];
    }
}
