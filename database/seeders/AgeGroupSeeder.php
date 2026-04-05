<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AgeGroupSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Детское питание' => ['0-3 месяца', '3-6 месяцев', '6-9 месяцев', '9-12 месяцев', '1-2 года', '2-3 года', '3+ лет'],
            'Игрушки'         => ['0-6 месяцев', '6-12 месяцев', '1-2 года', '2-3 года', '3-5 лет', '5+ лет'],
            'Одежда'          => ['Новорождённые', '0-6 месяцев', '6-12 месяцев', '1-2 года', '2-3 года'],
            'Купание'         => ['0-6 месяцев', '6-12 месяцев', '1-3 года'],
        ];

        $categories = Category::pluck('id', 'name');

        foreach ($data as $categoryName => $groups) {
            foreach ($groups as $group) {
                AgeGroup::create([
                    'category_id' => $categories[$categoryName],
                    'name'        => $group,
                ]);
            }
        }
    }
}