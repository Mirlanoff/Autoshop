<?php

namespace App\Http\Controllers;

use App\DTO\Product\ProductFilterDTO;
use App\Actions\Product\GetProductsAction;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, GetProductsAction $action)
    {
        $filters = ProductFilterDTO::fromRequest($request);

        $brands = Cache::remember('brands', 3600, fn () =>
            Brand::select('id', 'name')->get()
        );

        $categories = Cache::remember('categories', 3600, fn () =>
            Category::select('id', 'name')->get()
        );

        return Inertia::render('Products/Index', [
            'products'   => $action->execute($filters),
            'filters'    => $filters,
            'brands'     => $brands,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['brand:id,name', 'category:id,name'])
            ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['brand:id,name'])
            ->select(['id', 'name', 'slug', 'price', 'stock', 'brand_id', 'category_id', 'image'])
            ->limit(4)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'description' => $product->description,
                'image' => $product->image,
                'brand' => ['id' => $product->brand->id, 'name' => $product->brand->name],
                'category' => ['id' => $product->category->id, 'name' => $product->category->name],
            ],
            'related' => $related->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => (float) $p->price,
                'in_stock' => $p->stock > 0,
                'image' => $p->image,
                'brand' => ['name' => $p->brand->name],
            ]),
        ]);
    }
}
