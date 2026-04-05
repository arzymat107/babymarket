<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $categories = Category::all();
        $featured   = Product::with(['category', 'brand'])->latest()->take(8)->get();

        return Inertia::render('Home', [
            'categories' => $categories,
            'featured'   => $featured,
        ]);
    }
}