<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxMaster;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaxMasterController extends Controller
{
    public function index()
    {
        $taxRates = TaxMaster::with(['category', 'subcategory'])->ordered()->get();
        return view('admin.tax-master.index', compact('taxRates'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.tax-master.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|in:gst,vat',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'hsn_code' => 'nullable|string|max:20',
            'sac_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $slug = Str::slug($request->name);

        // Check if slug already exists
        if (TaxMaster::where('slug', $slug)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A tax rate with this name already exists.');
        }

        // Validate that either category or subcategory is selected
        if (!$request->category_id && !$request->subcategory_id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select either a category or subcategory.');
        }

        TaxMaster::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'tax_rate' => $request->tax_rate,
            'tax_type' => $request->tax_type,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'hsn_code' => $request->hsn_code,
            'sac_code' => $request->sac_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.tax-master.index')
            ->with('success', 'Tax rate created successfully.');
    }

    public function edit(TaxMaster $taxMaster)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.tax-master.edit', compact('taxMaster', 'categories', 'subcategories'));
    }

    public function update(Request $request, TaxMaster $taxMaster)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|in:gst,vat',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'hsn_code' => 'nullable|string|max:20',
            'sac_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Validate that either category or subcategory is selected
        if (!$request->category_id && !$request->subcategory_id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select either a category or subcategory.');
        }

        $taxMaster->update([
            'name' => $request->name,
            'description' => $request->description,
            'tax_rate' => $request->tax_rate,
            'tax_type' => $request->tax_type,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'hsn_code' => $request->hsn_code,
            'sac_code' => $request->sac_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.tax-master.index')
            ->with('success', 'Tax rate updated successfully.');
    }

    public function toggleStatus(TaxMaster $taxMaster)
    {
        $taxMaster->update([
            'is_active' => !$taxMaster->is_active
        ]);

        $status = $taxMaster->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Tax rate {$status} successfully.");
    }

    public function destroy(TaxMaster $taxMaster)
    {
        $taxMaster->delete();

        return redirect()->route('admin.tax-master.index')
            ->with('success', 'Tax rate deleted successfully.');
    }

    public function testCalculation(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'customer_state' => 'nullable|string',
        ]);

        $amount = $request->amount;
        $customerState = $request->customer_state;

        $taxRates = TaxMaster::active()->with(['category', 'subcategory'])->get();
        $calculations = [];

        foreach ($taxRates as $tax) {
            $calculations[] = [
                'tax' => $tax,
                'breakdown' => $tax->calculateTax($amount, $customerState)
            ];
        }

        return response()->json([
            'success' => true,
            'amount' => $amount,
            'customer_state' => $customerState,
            'company_state' => 'Gujarat',
            'calculations' => $calculations
        ]);
    }
}
