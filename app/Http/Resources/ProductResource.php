<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->stock_quantity > 0,
            'image_url' => $this->imageUrl(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'category_id' => $this->when(! $this->relationLoaded('category'), $this->category_id),
        ];
    }
}
