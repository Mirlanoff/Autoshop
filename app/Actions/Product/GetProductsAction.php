<?php

namespace App\Actions\Product;

use App\DTO\Product\ProductFilterDTO;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class GetProductsAction
{
    public function execute(ProductFilterDTO $filters)
    {
        $products = Product::query()
            ->select(['id', 'name', 'slug', 'price', 'brand_id', 'category_id', 'stock', 'image'])
            ->with([
                'brand:id,name',
                'category:id,name',
            ])
            ->when($filters->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
            )
            ->when($filters->brandId, fn ($q, $id) =>
                $q->where('brand_id', $id)
            )
            ->when($filters->categoryId, fn ($q, $id) =>
                $q->where('category_id', $id)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return ProductResource::collection($products);
    }
}
