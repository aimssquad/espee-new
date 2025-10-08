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
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Filter by subcategory
        if ($request->filled('subcategory')) {
            if (is_numeric($request->subcategory)) {
                $query->where('subcategory_id', $request->subcategory);
            } else {
                $subcategory = Subcategory::where('slug', $request->subcategory)->first();
                if ($subcategory) {
                    $query->where('subcategory_id', $subcategory->id);
                }
            }
        }

        // Filter by shape
        if ($request->filled('shape')) {
            if (is_numeric($request->shape)) {
                $query->where('shape_id', $request->shape);
            } else {
                $shape = Shape::where('slug', $request->shape)->first();
                if ($shape) {
                    $query->where('shape_id', $shape->id);
                }
            }
        }

        // Filter by color
        if ($request->filled('color')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('subcategory', function($subcategoryQuery) use ($searchTerm) {
                      $subcategoryQuery->where('name', 'like', "%{$searchTerm}%");
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

        // For AJAX requests (live search)
        if ($request->ajax()) {
            return view('products.partials.product-cards', compact('products'))->render();
        }

        // Get filter options
        $categories = Category::all();
        $shapes = Shape::all();
        $colors = Color::all();

        return view('products.index', compact('products', 'categories', 'shapes', 'colors'));
    }

    public function show($product)
    {
        // Handle both ID and slug-based routing
        if (is_numeric($product)) {
            $product = Product::findOrFail($product);
        } else {
            $product = Product::where('slug', $product)->firstOrFail();
        }

        $product->load(['variants.color', 'variants.images', 'category', 'subcategory', 'shape', 'highlights']);

        $relatedProducts = Product::with(['variants.color', 'variants.images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $highlights = $product->highlights()->where('is_active', true)->orderBy('position')->get();
        return view('products.show', compact('product', 'relatedProducts', 'highlights'));
    }
}
