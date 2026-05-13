<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'account_no' => $this->account_no,
            'name' => $this->name,
            'initial_balance' => $this->initial_balance,
            'total_balance' => $this->total_balance,
            'note' => $this->note,
        ];
    }
}
