<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'thumbnail' =>$this->thumbnail ? $this->thumbnail->file : asset('defualt/defualt.jpg'),
            'parent_category_id' => $this->parentCategory?->id,
            'parent_category_name' => $this->parentCategory?->name,
            'parent_category_thumbnail' => $this->parentCategory &&  $this->parentCategory->thumbnail ? $this->parentCategory->thumbnail->file : asset('defualt/defualt.jpg'),
            'total_products' => $this->product->count()
        ];
    }
}
