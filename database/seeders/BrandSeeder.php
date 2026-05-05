<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
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
            'Volkswagen',
            'Hyundai',
            'Kia',
            'Nissan',
            'Honda',
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
