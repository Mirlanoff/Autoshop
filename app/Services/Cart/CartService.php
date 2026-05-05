<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const KEY = 'cart';

    public function get(): array
    {
        return Session::get(self::KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->get();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $item = new CartItemDTO(
                productId: $product->id,
                name: $product->name,
                price: (float) $product->price,
                quantity: $quantity
            );

            $cart[$product->id] = $item->toArray();
        }

        Session::put(self::KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->get();
        unset($cart[$productId]);
        Session::put(self::KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->get();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, $quantity);
        }

        Session::put(self::KEY, $cart);
    }

    public function total(): float
    {
        return collect($this->get())
            ->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function count(): int
    {
        return collect($this->get())
            ->sum(fn ($item) => $item['quantity']);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }
}
