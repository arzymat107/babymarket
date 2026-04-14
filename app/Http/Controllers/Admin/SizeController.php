<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function store(Request $request, Category $category): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->sizes()->create(['name' => $request->name]);

        return back();
    }

    public function update(Request $request, Size $size): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $size->update(['name' => $request->name]);

        return back();
    }

    public function destroy(Size $size): RedirectResponse
    {
        $size->delete();

        return back();
    }
}