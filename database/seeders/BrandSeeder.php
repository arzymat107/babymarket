<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Детское питание' => ['Gerber', 'Heinz', 'Nutrilon', 'Bebi Premium', 'HiPP', 'Semper'],
            'Подгузники'      => ['Pampers', 'Huggies', 'Libero', 'Merries', 'Honest'],
            'Игрушки'         => ['Fisher-Price', 'Bright Starts', 'LEGO', 'Chicco', 'Playgro'],
            'Одежда'          => ['Carter\'s', 'H&M Baby', 'Mothercare', 'Lupilu'],
            'Коляски'         => ['Britax', 'Chicco', 'Bugaboo', 'Peg Perego', 'Cybex'],
            'Купание'         => ['Johnson\'s Baby', 'Mustela', 'Bubchen', 'Weleda'],
        ];

        $categories = Category::pluck('id', 'name');

        foreach ($data as $categoryName => $brands) {
            foreach ($brands as $brand) {
                Brand::create([
                    'category_id' => $categories[$categoryName],
                    'name'        => $brand,
                ]);
            }
        }
    }
}