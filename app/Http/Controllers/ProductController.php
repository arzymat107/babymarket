<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with(['category', 'brand', 'size', 'ageGroup']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('size_id')) {
            $query->where('size_id', $request->size_id);
        }
        if ($request->filled('age_group_id')) {
            $query->where('age_group_id', $request->age_group_id);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::with(['brands', 'sizes', 'ageGroups'])->get();

        return Inertia::render('Catalog', [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $request->only(['category_id', 'brand_id', 'size_id', 'age_group_id', 'min_price', 'max_price']),
        ]);
    }

    public function show(Product $product): Response
    {
        $product->load(['category', 'brand', 'size', 'ageGroup']);

        $related = Product::with(['brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return Inertia::render('Product/Show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}