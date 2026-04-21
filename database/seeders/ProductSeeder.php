<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();
        $categories = Category::all();

        for ($i = 1; $i <= 50; $i++) {
            $name = "Запчасть {$i}";

            Product::create([
                'name' => $name,
                'slug' => Str::slug($name . '-' . $i),

                'brand_id' => $brands->random()->id,
                'category_id' => $categories->random()->id,

                'price' => rand(100, 5000),
                'stock' => rand(0, 100),

                'description' => 'Описание товара ' . $i,
            ]);
        }
    }
}
