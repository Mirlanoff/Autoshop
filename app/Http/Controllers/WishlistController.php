<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()
            ->wishlist()
            ->with(['brand:id,name', 'category:id,name'])
            ->select(['products.id', 'name', 'slug', 'price', 'stock', 'brand_id', 'category_id', 'image'])
            ->latest('wishlists.created_at')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => (float) $p->price,
                'in_stock' => $p->stock > 0,
                'image' => $p->image,
                'brand' => ['name' => $p->brand->name],
                'category' => ['name' => $p->category->name],
            ]);

        return Inertia::render('Wishlist/Index', [
            'products' => $products,
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = $request->user();
        $productId = $request->input('product_id');

        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            $user->wishlist()->detach($productId);
            return back()->with('success', 'Удалено из избранного');
        }

        $user->wishlist()->attach($productId);
        return back()->with('success', 'Добавлено в избранное');
    }
}
