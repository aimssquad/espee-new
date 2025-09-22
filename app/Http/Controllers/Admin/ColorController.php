<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::withCount('productVariants')->paginate(10);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors',
            'hex_code' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Color::create($validated);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color created successfully.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'hex_code' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $color->update($validated);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        if ($color->productVariants()->exists()) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Cannot delete color with product variants.');
        }

        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}