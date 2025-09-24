<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Shape;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants.color', 'variants.images', 'category', 'subcategory', 'shape']);

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by subcategory
        if ($request->filled('subcategory')) {
            $subcategory = Subcategory::where('slug', $request->subcategory)->first();
            if ($subcategory) {
                $query->where('subcategory_id', $subcategory->id);
            }
        }

        // Filter by shape
        if ($request->filled('shape')) {
            $query->where('shape_id', $request->shape);
        }

        // Filter by color
        if ($request->filled('color')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model_no', 'like', "%{$search}%")
                  ->orWhereHas('variants', function($sq) use ($search) {
                      $sq->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $products = $query->get()->sortBy(function($product) {
                    return $product->min_price;
                })->values();
                break;
            case 'price_high':
                $products = $query->get()->sortByDesc(function($product) {
                    return $product->max_price;
                })->values();
                break;
            case 'name':
                $query->orderBy('name');
                $products = $query->paginate(12);
                break;
            default:
                $query->latest();
                $products = $query->paginate(12);
        }

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'products' => view('partials.product-grid', compact('products'))->render(),
                'pagination' => $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->links()->render() : ''
            ]);
        }

        // Get filter options
        $categories = Category::all();
        $shapes = Shape::all();
        $colors = Color::all();

        return view('products.index', compact('products', 'categories', 'shapes', 'colors'));
    }

    public function show(Product $product)
    {
        $product->load(['variants.color', 'variants.images', 'category', 'subcategory', 'shape']);

        $relatedProducts = Product::with(['variants.color', 'variants.images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
