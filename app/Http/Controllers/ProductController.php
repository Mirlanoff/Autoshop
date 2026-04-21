<?php

namespace App\Http\Controllers;

use App\DTO\Product\ProductFilterDTO;
use App\Actions\Product\GetProductsAction;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Импортируем фасад кеша
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, GetProductsAction $action)
    {
        $filters = ProductFilterDTO::fromRequest($request);

        // Кешируем список брендов на 1 час (3600 секунд)
        $brands = Cache::remember('brands', 3600, fn () =>
        Brand::select('id', 'name')->get()
        );

        // Кешируем список категорий на 1 час
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
}
