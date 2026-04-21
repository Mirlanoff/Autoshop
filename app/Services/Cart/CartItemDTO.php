<?php
namespace App\Services\Cart;

class CartItemDTO
{
public function __construct(
public int $productId,
public string $name,
public float $price,
public int $quantity,
) {}

public function toArray(): array
{
return [
'product_id' => $this->productId,
'name' => $this->name,
'price' => $this->price,
'quantity' => $this->quantity,
];
}
}
