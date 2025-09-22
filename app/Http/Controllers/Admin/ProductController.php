<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Shape;
use App\Models\Color;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'shape', 'variants'])
            ->withCount('variants')
            ->paginate(10);
            
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $subcategories = Subcategory::pluck('name', 'id');
        $shapes = Shape::pluck('name', 'id');
        $colors = Color::all();
        
        return view('admin.products.create', compact('categories', 'subcategories', 'shapes', 'colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'shape_id' => 'nullable|exists:shapes,id',
            'name' => 'required|string|max:255',
            'model_no' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create($validated);

            foreach ($request->variants as $variantData) {
                $variant = [
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'color_id' => $variantData['color_id'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ];

                if (isset($variantData['image'])) {
                    $path = $variantData['image']->store('products', 'public');
                    $variant['image'] = $path;
                }

                ProductVariant::create($variant);
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::pluck('name', 'id');
        $subcategories = Subcategory::pluck('name', 'id');
        $shapes = Shape::pluck('name', 'id');
        $colors = Color::all();
        $product->load('variants.color');
        
        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'shapes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'shape_id' => 'nullable|exists:shapes,id',
            'name' => 'required|string|max:255',
            'model_no' => 'required|string|unique:products,model_no,' . $product->id,
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete variant images
        foreach ($product->variants as $variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function variants(Product $product)
    {
        $product->load('variants.color');
        $colors = Color::all();
        
        return view('admin.products.variants', compact('product', 'colors'));
    }

    public function addVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:product_variants',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['product_id'] = $product->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        ProductVariant::create($validated);

        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Variant added successfully.');
    }

    public function updateVariant(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $variant->update($validated);

        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Variant updated successfully.');
    }

    public function deleteVariant(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Variant deleted successfully.');
    }
}