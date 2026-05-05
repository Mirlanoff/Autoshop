<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Двигатель',
            'Подвеска',
            'Тормоза',
            'Фильтры',
            'Масла и жидкости',
            'Электрика',
            'Кузов',
            'Трансмиссия',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
