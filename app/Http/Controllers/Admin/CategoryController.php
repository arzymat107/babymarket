<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Categories/Index', [
            'categories' => Category::withCount('products')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->only('name', 'image_url'));

        return back();
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->only('name', 'image_url'));

        return back();
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back();
    }
}