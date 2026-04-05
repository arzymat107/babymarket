<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Checkout');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $productIds = collect($request->items)->pluck('id');
        $products   = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $subtotal     = 0;
        $shippingCost = 200.00;
        $itemsData    = [];

        foreach ($request->items as $item) {
            $product      = $products[$item['id']];
            $itemSubtotal = $product->price * $item['quantity'];
            $subtotal    += $itemSubtotal;

            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $product->price,
                'subtotal'   => $itemSubtotal,
            ];
        }

        $order = Order::create([
            'customer_name'    => $request->customer_name,
            'customer_phone'   => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_cost'    => $shippingCost,
            'total_amount'     => $subtotal + $shippingCost,
            'paid'             => false,
            'status'           => 'pending',
        ]);

        foreach ($itemsData as $item) {
            OrderItem::create(['order_id' => $order->id, ...$item]);
        }

        return redirect()->route('orders.success', $order->id);
    }

    public function success(Order $order): Response
    {
        $order->load('items.product');

        return Inertia::render('Orders/Success', [
            'order' => $order,
        ]);
    }
}