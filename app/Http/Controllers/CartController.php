<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Services\Cart\CartService;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return Inertia::render('Cart/Index', [
            'items' => $cart->get(),
            'total' => $cart->total(),
        ]);
    }

    public function add(Request $request, CartService $cart)
    {
        $product = Product::findOrFail($request->product_id);

        $cart->add($product);

        return back();
    }

    public function remove(int $id, CartService $cart)
    {
        $cart->remove($id);

        return back();
    }

    public function update(Request $request, int $id, CartService $cart)
    {
        $cart->update($id, $request->quantity);

        return back();
    }
}
