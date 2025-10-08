<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductHighlightController extends Controller
{
    public function store(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'nullable|string',
                'position' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'image' => 'nullable|image|max:3072',
            ]);

            $data = [
                'product_id' => $product->id,
                'title' => $validated['title'] ?? null,
                'text' => $validated['text'] ?? null,
                'position' => $validated['position'] ?? 0,
                'is_active' => $validated['is_active'] ?? true,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('product-highlights', 'public');
            }

            ProductHighlight::create($data);

            return redirect()->back()->with('success', 'Highlight added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding highlight: ' . $e->getMessage());
        }
    }

    public function destroy(ProductHighlight $highlight)
    {
        if ($highlight->image) {
            Storage::disk('public')->delete($highlight->image);
        }
        $highlight->delete();

        return redirect()->back()->with('success', 'Highlight deleted successfully.');
    }
}


