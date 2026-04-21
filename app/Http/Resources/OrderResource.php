<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer_name' => $this->customer_name,
            'phone' => $this->phone,

            'total' => (float) $this->total,
            'status' => $this->status,

            'created_at' => $this->created_at->format('d.m.Y H:i'),

            // товары (только если загружены)
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => (float) $item->price,
                    'quantity' => $item->quantity,
                ]);
            }),
        ];
    }
}
