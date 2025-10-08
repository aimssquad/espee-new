<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\VideoSetting;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::with(['products' => function($query) {
            $query->with(['variants', 'variants.images'])->limit(4);
        }])->whereIn('slug', ['sunglasses', 'frames'])->get();

        $featuredProducts = Product::with(['variants.color', 'variants.images', 'category'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $videoSetting = VideoSetting::where('is_active', true)->first();

        return view('home', compact('featuredCategories', 'featuredProducts', 'videoSetting'));
    }
}
