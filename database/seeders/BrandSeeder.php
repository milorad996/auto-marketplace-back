<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Audi',
            'BMW',
            'Mercedes-Benz',
            'Toyota',
            'Honda',
            'Ford',
            'Chevrolet',
            'Hyundai',
            'Kia',
            'Volkswagen',
            'Nissan',
            'Lexus',
            'Mazda',
            'Porsche',
            'Tesla',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'brand_name' => $brand,
            ]);
        }
    }
}
