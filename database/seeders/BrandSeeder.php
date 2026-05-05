<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Toyota',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Honda',
            'Hyundai',
            'Kia',
            'Volkswagen',
            'Nissan',
            'Ford',
        ];

        foreach ($brands as $name) {
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
