<?php

namespace App\Http\Controllers;

use App\Actions\Product\GetProductsAction;
use App\DTO\Product\ProductFilterDTO;
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

    public function show(Product $product)
    {
        $product->load(['brand:id,name', 'category:id,name']);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->select(['id', 'name', 'slug', 'price', 'stock', 'brand_id', 'category_id', 'image'])
            ->with(['brand:id,name', 'category:id,name'])
            ->limit(4)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
