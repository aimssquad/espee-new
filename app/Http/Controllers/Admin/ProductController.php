<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Shape;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory', 'shape', 'variants'])
            ->withCount('variants')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subcategory', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('shape', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.products.partials.products-table', compact('products'))->render(),
                'pagination' => $products->links()->render()
            ]);
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $shapes = Shape::all();
        $colors = Color::all();
        $genderOptions = Product::getGenderOptions();

        return view('admin.products.create', compact('categories', 'subcategories', 'shapes', 'colors', 'genderOptions'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id',
                'shape_id' => 'nullable|exists:shapes,id',
                'gender' => 'required|in:men,women,unisex',
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:products',
                'model_no' => 'required|string|unique:products',
                'description' => 'nullable|string',
                'base_price' => 'required|numeric|min:0',
            ]);

            $product = Product::create($validated);

            return redirect()->route('admin.products.variants', $product)
                ->with('success', 'Product created successfully. Now add variants for this product.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $shapes = Shape::all();
        $colors = Color::all();
        $genderOptions = Product::getGenderOptions();
        $product->load(['variants.color', 'highlights']);

        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'shapes', 'colors', 'genderOptions'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'shape_id' => 'nullable|exists:shapes,id',
            'gender' => 'required|in:men,women,unisex',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
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
        $product->load(['variants.color', 'variants.images']);
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
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['product_id'] = $product->id;

        $variant = ProductVariant::create($validated);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');

                \App\Models\ProductImage::create([
                    'product_variant_id' => $variant->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

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
            'images.*' => 'nullable|image|max:2048',
        ]);

        $variant->update($validated);

        // Handle multiple images
        if ($request->hasFile('images')) {
            // Delete existing images
            foreach ($variant->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Add new images
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');

                \App\Models\ProductImage::create([
                    'product_variant_id' => $variant->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Variant updated successfully.');
    }

    public function deleteVariant(Product $product, ProductVariant $variant)
    {
        // Delete all associated images
        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $variant->delete();

        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Variant deleted successfully.');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function setPrimaryImage(ProductImage $image)
    {
        // Remove primary status from other images of the same variant
        ProductImage::where('product_variant_id', $image->product_variant_id)
            ->where('id', '!=', $image->id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }
}
