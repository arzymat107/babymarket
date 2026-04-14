<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeGroup;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    public function store(Request $request, Category $category): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->ageGroups()->create(['name' => $request->name]);

        return back();
    }

    public function update(Request $request, AgeGroup $ageGroup): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $ageGroup->update(['name' => $request->name]);

        return back();
    }

    public function destroy(AgeGroup $ageGroup): RedirectResponse
    {
        $ageGroup->delete();

        return back();
    }
}