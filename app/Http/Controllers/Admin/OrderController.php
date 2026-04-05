<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return Inertia::render('admin/Orders/Index', [
            'orders'  => $query->paginate(15)->withQueryString(),
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load('items.product');

        return Inertia::render('admin/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back();
    }

    public function updatePaid(Order $order): RedirectResponse
    {
        $order->update(['paid' => ! $order->paid]);

        return back();
    }
}