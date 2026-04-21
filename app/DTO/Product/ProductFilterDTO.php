<?php
namespace App\DTO\Product;

use Illuminate\Http\Request;

class ProductFilterDTO
{
public function __construct(
public readonly ?string $search,
public readonly ?int $brandId,
public readonly ?int $categoryId,
) {}

public static function fromRequest(Request $request): self
{
return new self(
search: $request->string('search')->toString() ?: null,
brandId: $request->integer('brand_id') ?: null,
categoryId: $request->integer('category_id') ?: null,
);
}
}
