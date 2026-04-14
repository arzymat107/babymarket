<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function create(): Response
    {
        return Inertia::render('admin/Categories/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            $data['image_url'] = Storage::url($request->file('image')->store('categories', 'public'));
        }

        Category::create($data);

        return redirect()->route('admin.categories.index');
    }

    public function show(Category $category): Response
    {
        $category->load(['brands', 'sizes', 'ageGroups']);
        $products = $category->products()->with(['brand', 'size', 'ageGroup'])->latest()->paginate(15);

        return Inertia::render('admin/Categories/Show', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('admin/Categories/Form', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            if ($category->image_url) {
                Storage::disk('public')->delete($this->pathFromUrl($category->image_url));
            }
            $data['image_url'] = Storage::url($request->file('image')->store('categories', 'public'));
        }

        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image_url) {
            Storage::disk('public')->delete($this->pathFromUrl($category->image_url));
        }

        $category->delete();

        return back();
    }

    private function pathFromUrl(string $url): string
    {
        return ltrim(parse_url($url, PHP_URL_PATH), '/storage/');
    }
}