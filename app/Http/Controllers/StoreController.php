<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::with(['products.variants.color', 'products.variants.images'])->take(2)->get();
        $featuredProducts = Product::with(['variants.color', 'variants.images', 'category'])
            ->whereHas('variants', function($query) {
                $query->where('stock', '>', 0);
            })
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('store.index', compact('featuredCategories', 'featuredProducts'));
    }
}
