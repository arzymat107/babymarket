<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::pluck('id', 'name');

        $orders = [
            [
                'customer_name'    => 'Айгуль Мамытова',
                'customer_phone'   => '+996700123456',
                'shipping_address' => 'г. Бишкек, ул. Манаса 25, кв. 10',
                'shipping_cost'    => 200.00,
                'paid'             => true,
                'status'           => 'delivered',
                'items' => [
                    ['name' => 'Pampers Active Baby 3 54шт', 'qty' => 2],
                    ['name' => 'Gerber Пюре яблоко 80г',     'qty' => 5],
                ],
            ],
            [
                'customer_name'    => 'Нурбек Асанов',
                'customer_phone'   => '+996555987654',
                'shipping_address' => 'г. Ош, ул. Ленина 12',
                'shipping_cost'    => 300.00,
                'paid'             => false,
                'status'           => 'shipped',
                'items' => [
                    ['name' => 'Bright Starts Развивающий коврик', 'qty' => 1],
                    ['name' => 'Fisher-Price Погремушка',           'qty' => 2],
                ],
            ],
            [
                'customer_name'    => 'Зарина Токтосунова',
                'customer_phone'   => '+996777456789',
                'shipping_address' => 'г. Бишкек, мкр. Асанбай 5, кв. 32',
                'shipping_cost'    => 200.00,
                'paid'             => false,
                'status'           => 'pending',
                'items' => [
                    ['name' => 'Nutrilon смесь молочная 1 400г',       'qty' => 3],
                    ['name' => 'Carter\'s Боди с длинным рукавом р.56', 'qty' => 2],
                    ['name' => 'Lupilu Шапочка хлопковая 0-3м',         'qty' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($items as $item) {
                $product  = Product::find($products[$item['name']]);
                $itemSubtotal = $product->price * $item['qty'];
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'unit_price' => $product->price,
                    'subtotal'   => $itemSubtotal,
                ];
            }

            $order = Order::create([
                ...$orderData,
                'total_amount' => $subtotal + $orderData['shipping_cost'],
            ]);

            foreach ($itemsToCreate as $item) {
                OrderItem::create(['order_id' => $order->id, ...$item]);
            }
        }
    }
}