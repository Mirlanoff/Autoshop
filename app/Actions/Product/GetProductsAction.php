<?php

namespace App\Actions\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\DTO\Product\ProductFilterDTO;

class GetProductsAction
{
    public function execute(ProductFilterDTO $filters)
    {
        $products = Product::query()
            // 1. Ограничиваем выборку полей самого продукта (только то, что нужно для карточки)
            ->select(['id', 'name', 'slug', 'price', 'brand_id', 'category_id', 'stock', 'image'])

            // 2. Ограничиваем поля в связанных таблицах (избегаем N+1 и лишних данных)
            ->with([
                'brand:id,name',
                'category:id,name'
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

        // 3. Возвращаем коллекцию через ресурс (ресурс сам отфильтрует лишнее, если нужно)
        return ProductResource::collection($products);
    }
}
