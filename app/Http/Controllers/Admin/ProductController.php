<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim();

        return Inertia::render('admin/Products/Index', [
            'products' => Product::with(['category', 'brand'])
                ->when($search, fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($search) . '%']))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => (string) $search,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('admin/Products/Form', [
            'categories'    => Category::with(['brands', 'sizes', 'ageGroups'])->get(),
            'from_category' => $request->query('from_category'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'brand_id'     => 'nullable|exists:brands,id',
            'size_id'      => 'nullable|exists:sizes,id',
            'age_group_id' => 'nullable|exists:age_groups,id',
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:4096',
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        $product = Product::create($data);

        if ($request->filled('from_category')) {
            return redirect()->route('admin.categories.show', $request->from_category);
        }

        return redirect()->route('admin.products.index');
    }

    public function show(Request $request, Product $product): Response
    {
        $product->load(['category', 'brand', 'size', 'ageGroup']);

        $orders = $product->orderItems()
            ->with('order')
            ->latest()
            ->get()
            ->map(fn ($item) => [
                'order_id'   => $item->order_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal'   => $item->subtotal,
                'status'     => $item->order->status,
                'paid'       => $item->order->paid,
                'created_at' => $item->order->created_at->format('d.m.Y'),
            ]);

        return Inertia::render('admin/Products/Show', [
            'product'       => $product,
            'orders'        => $orders,
            'from_category' => $request->query('from_category'),
        ]);
    }

    public function edit(Request $request, Product $product): Response
    {
        return Inertia::render('admin/Products/Form', [
            'product'       => $product,
            'categories'    => Category::with(['brands', 'sizes', 'ageGroups'])->get(),
            'from_category' => $request->query('from_category'),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'brand_id'     => 'nullable|exists:brands,id',
            'size_id'      => 'nullable|exists:sizes,id',
            'age_group_id' => 'nullable|exists:age_groups,id',
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:4096',
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($product->image_url) {
                Storage::disk('public')->delete($this->pathFromUrl($product->image_url));
            }
            $data['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        $product->update($data);

        if ($request->filled('from_category')) {
            return redirect()->route('admin.categories.show', $request->from_category);
        }

        return redirect()->route('admin.products.index');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        if ($product->image_url) {
            Storage::disk('public')->delete($this->pathFromUrl($product->image_url));
        }

        $product->delete();

        if ($request->filled('from_category')) {
            return redirect()->route('admin.categories.show', $request->from_category);
        }

        return redirect()->route('admin.products.index');
    }

    private function pathFromUrl(string $url): string
    {
        return ltrim(parse_url($url, PHP_URL_PATH), '/storage/');
    }
}