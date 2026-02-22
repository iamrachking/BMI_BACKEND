<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->cartItems ?? collect();
        $subtotal = 0;
        $itemsCount = 0;
        foreach ($items as $item) {
            $item->loadMissing('product');
            $subtotal += (float) $item->product->price * $item->quantity;
            $itemsCount += $item->quantity;
        }

        return [
            'id' => $this->id,
            'items_count' => $itemsCount,
            'subtotal' => round($subtotal, 2),
            'items' => CartItemResource::collection($this->whenLoaded('cartItems')),
        ];
    }
}
