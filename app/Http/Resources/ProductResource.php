<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'slug'     => $this->slug,
            'price'    => (float) $this->price,
            'stock'    => $this->stock,
            'in_stock' => $this->stock > 0,
            'image'    => $this->image,

            'brand' => [
                'id'   => $this->brand->id,
                'name' => $this->brand->name,
            ],

            'category' => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],
        ];
    }
}
