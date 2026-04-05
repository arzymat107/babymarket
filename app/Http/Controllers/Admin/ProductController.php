<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Products/Index', [
            'products' => Product::with(['category', 'brand'])->latest()->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Products/Form', [
            'categories' => Category::with(['brands', 'sizes', 'ageGroups'])->get(),
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
            'image_url'    => 'nullable|url|max:500',
        ]);

        Product::create($data);

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('admin/Products/Form', [
            'product'    => $product,
            'categories' => Category::with(['brands', 'sizes', 'ageGroups'])->get(),
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
            'image_url'    => 'nullable|url|max:500',
        ]);

        $product->update($data);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back();
    }
}