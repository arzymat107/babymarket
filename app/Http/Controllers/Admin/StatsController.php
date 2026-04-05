<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class StatsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'total_orders'    => Order::count(),
                'pending_orders'  => Order::where('status', 'pending')->count(),
                'total_revenue'   => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
                'total_products'  => Product::count(),
                'total_categories' => Category::count(),
            ],
            'recent_orders' => Order::latest()->take(5)->get(),
        ]);
    }
}