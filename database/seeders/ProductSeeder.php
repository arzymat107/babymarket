<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $brands     = Brand::pluck('id', 'name');
        $sizes      = Size::pluck('id', 'name');
        $ageGroups  = AgeGroup::pluck('id', 'name');

        $products = [
            // Детское питание
            [
                'category' => 'Детское питание', 'brand' => 'Gerber',
                'age_group' => '6-9 месяцев',
                'name' => 'Gerber Пюре яблоко 80г', 'price' => 150.00,
            ],
            [
                'category' => 'Детское питание', 'brand' => 'Heinz',
                'age_group' => '6-9 месяцев',
                'name' => 'Heinz Каша молочная рисовая 200г', 'price' => 280.00,
            ],
            [
                'category' => 'Детское питание', 'brand' => 'Nutrilon',
                'age_group' => '0-3 месяца',
                'name' => 'Nutrilon смесь молочная 1 400г', 'price' => 1200.00,
            ],
            [
                'category' => 'Детское питание', 'brand' => 'Bebi Premium',
                'age_group' => '6-9 месяцев',
                'name' => 'Bebi Premium пюре банан 100г', 'price' => 120.00,
            ],
            [
                'category' => 'Детское питание', 'brand' => 'HiPP',
                'age_group' => '9-12 месяцев',
                'name' => 'HiPP Каша овсяная с фруктами 200г', 'price' => 320.00,
            ],

            // Подгузники
            [
                'category' => 'Подгузники', 'brand' => 'Pampers',
                'size' => 'M (6-11кг)',
                'name' => 'Pampers Active Baby 3 54шт', 'price' => 1500.00,
            ],
            [
                'category' => 'Подгузники', 'brand' => 'Huggies',
                'size' => 'S (3-8кг)',
                'name' => 'Huggies Classic 2 82шт', 'price' => 1350.00,
            ],
            [
                'category' => 'Подгузники', 'brand' => 'Libero',
                'size' => 'L (9-14кг)',
                'name' => 'Libero Comfort 4 44шт', 'price' => 980.00,
            ],
            [
                'category' => 'Подгузники', 'brand' => 'Merries',
                'size' => 'Newborn (до 5кг)',
                'name' => 'Merries Newborn 90шт', 'price' => 1800.00,
            ],

            // Игрушки
            [
                'category' => 'Игрушки', 'brand' => 'Fisher-Price',
                'age_group' => '0-6 месяцев',
                'name' => 'Fisher-Price Погремушка', 'price' => 450.00,
            ],
            [
                'category' => 'Игрушки', 'brand' => 'Bright Starts',
                'age_group' => '0-6 месяцев',
                'name' => 'Bright Starts Развивающий коврик', 'price' => 2800.00,
            ],
            [
                'category' => 'Игрушки', 'brand' => 'Chicco',
                'age_group' => '1-2 года',
                'name' => 'Chicco Кубики мягкие 6шт', 'price' => 350.00,
            ],
            [
                'category' => 'Игрушки', 'brand' => 'LEGO',
                'age_group' => '2-3 года',
                'name' => 'LEGO Duplo Базовый набор', 'price' => 3200.00,
            ],

            // Одежда
            [
                'category' => 'Одежда', 'brand' => 'Carter\'s',
                'size' => '0-3м (56)', 'age_group' => 'Новорождённые',
                'name' => 'Carter\'s Боди с длинным рукавом р.56', 'price' => 320.00,
            ],
            [
                'category' => 'Одежда', 'brand' => 'H&M Baby',
                'size' => '6-9м (68)', 'age_group' => '6-12 месяцев',
                'name' => 'H&M Комбинезон флисовый р.68', 'price' => 850.00,
            ],
            [
                'category' => 'Одежда', 'brand' => 'Lupilu',
                'size' => '0-3м (56)', 'age_group' => 'Новорождённые',
                'name' => 'Lupilu Шапочка хлопковая 0-3м', 'price' => 180.00,
            ],

            // Коляски
            [
                'category' => 'Коляски', 'brand' => 'Britax',
                'size' => 'Стандарт',
                'name' => 'Britax B-Agile прогулочная коляска', 'price' => 28000.00,
            ],
            [
                'category' => 'Коляски', 'brand' => 'Chicco',
                'size' => 'Люлька',
                'name' => 'Chicco Люлька-переноска', 'price' => 4500.00,
            ],

            // Купание
            [
                'category' => 'Купание', 'brand' => 'Johnson\'s Baby',
                'age_group' => '0-6 месяцев',
                'name' => 'Johnson\'s Baby Шампунь 300мл', 'price' => 380.00,
            ],
            [
                'category' => 'Купание', 'brand' => 'Mustela',
                'age_group' => '0-6 месяцев',
                'name' => 'Mustela Гель для купания 200мл', 'price' => 950.00,
            ],
            [
                'category' => 'Купание', 'brand' => 'Bubchen',
                'age_group' => '0-6 месяцев',
                'name' => 'Bubchen Детский крем 75мл', 'price' => 290.00,
            ],
        ];

        foreach ($products as $p) {
            Product::create([
                'category_id'  => $categories[$p['category']],
                'brand_id'     => $brands[$p['brand']] ?? null,
                'size_id'      => isset($p['size']) ? ($sizes[$p['size']] ?? null) : null,
                'age_group_id' => isset($p['age_group']) ? ($ageGroups[$p['age_group']] ?? null) : null,
                'name'         => $p['name'],
                'price'        => $p['price'],
                'image_url'    => null,
            ]);
        }
    }
}