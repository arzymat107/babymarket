<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Детское питание', 'image_url' => null],
            ['name' => 'Подгузники',       'image_url' => null],
            ['name' => 'Игрушки',          'image_url' => null],
            ['name' => 'Одежда',           'image_url' => null],
            ['name' => 'Коляски',          'image_url' => null],
            ['name' => 'Купание',          'image_url' => null],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}