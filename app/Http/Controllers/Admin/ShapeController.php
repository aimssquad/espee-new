<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shape;
use Illuminate\Http\Request;

class ShapeController extends Controller
{
    public function index()
    {
        $shapes = Shape::withCount('products')->paginate(10);
        return view('admin.shapes.index', compact('shapes'));
    }

    public function create()
    {
        return view('admin.shapes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shapes',
        ]);

        Shape::create($validated);

        return redirect()->route('admin.shapes.index')
            ->with('success', 'Shape created successfully.');
    }

    public function edit(Shape $shape)
    {
        return view('admin.shapes.edit', compact('shape'));
    }

    public function update(Request $request, Shape $shape)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shapes,name,' . $shape->id,
        ]);

        $shape->update($validated);

        return redirect()->route('admin.shapes.index')
            ->with('success', 'Shape updated successfully.');
    }

    public function destroy(Shape $shape)
    {
        if ($shape->products()->exists()) {
            return redirect()->route('admin.shapes.index')
                ->with('error', 'Cannot delete shape with products.');
        }

        $shape->delete();

        return redirect()->route('admin.shapes.index')
            ->with('success', 'Shape deleted successfully.');
    }
}