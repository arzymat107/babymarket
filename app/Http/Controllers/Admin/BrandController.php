<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function store(Request $request, Category $category): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->brands()->create(['name' => $request->name]);

        return back();
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $brand->update(['name' => $request->name]);

        return back();
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return back();
    }
}