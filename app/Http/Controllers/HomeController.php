<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::with(['products' => function($query) {
            $query->with('variants')->limit(4);
        }])->whereIn('slug', ['sunglasses', 'frames'])->get();

        $featuredProducts = Product::with('variants.color', 'category')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('home', compact('featuredCategories', 'featuredProducts'));
    }
}