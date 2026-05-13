<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPurchase = $this->purchases()->where('shop_id', $this->mainShop()->id)->count();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'total_purchages' => (int) $totalPurchase,
            'address' => $this->address,
        ];
    }
    protected function mainShop()
    {
        $user = auth()->user();
        $mainShop = $user->shopUser->first();

        return match ($mainShop) {
            null => $user->shop,
            default => $mainShop
        };
    }
}
