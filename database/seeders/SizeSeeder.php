<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Подгузники' => ['Newborn (до 5кг)', 'S (3-8кг)', 'M (6-11кг)', 'L (9-14кг)', 'XL (12-17кг)', 'XXL (15кг+)'],
            'Одежда'     => ['0-3м (56)', '3-6м (62)', '6-9м (68)', '9-12м (74)', '12-18м (80)', '18-24м (86)', '2-3г (92)'],
            'Коляски'    => ['Стандарт', 'Компактная', 'Люлька'],
        ];

        $categories = Category::pluck('id', 'name');

        foreach ($data as $categoryName => $sizes) {
            foreach ($sizes as $size) {
                Size::create([
                    'category_id' => $categories[$categoryName],
                    'name'        => $size,
                ]);
            }
        }
    }
}