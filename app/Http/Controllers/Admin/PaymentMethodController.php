<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethodMaster::ordered()->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $slug = Str::slug($request->name);

        // Check if slug already exists
        if (PaymentMethodMaster::where('slug', $slug)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A payment method with this name already exists.');
        }

        PaymentMethodMaster::create([
            'name' => $request->name,
            'slug' => $slug,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethodMaster $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethodMaster $paymentMethod)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $paymentMethod->update([
            'display_name' => $request->display_name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function updateCredentials(Request $request, PaymentMethodMaster $paymentMethod)
    {
        $credentials = [];

        if ($paymentMethod->isRazorpay()) {
            $request->validate([
                'razorpay_key_id' => 'required|string',
                'razorpay_key_secret' => 'required|string',
                'razorpay_test_mode' => 'boolean',
            ]);

            $credentials = [
                'key_id' => $request->razorpay_key_id,
                'key_secret' => $request->razorpay_key_secret,
                'test_mode' => $request->has('razorpay_test_mode'),
            ];
        }

        if ($paymentMethod->isPayU()) {
            $request->validate([
                'payu_merchant_key' => 'required|string',
                'payu_merchant_salt' => 'required|string',
                'payu_authorization_header' => 'nullable|string',
                'payu_test_mode' => 'boolean',
            ]);

            $credentials = [
                'merchant_key' => $request->payu_merchant_key,
                'merchant_salt' => $request->payu_merchant_salt,
                'authorization_header' => $request->payu_authorization_header,
                'test_mode' => $request->has('payu_test_mode'),
            ];
        }

        if ($paymentMethod->isCOD()) {
            $request->validate([
                'cod_minimum_amount' => 'nullable|numeric|min:0',
                'cod_maximum_amount' => 'nullable|numeric|min:0|gte:cod_minimum_amount',
                'cod_instructions' => 'nullable|string',
            ]);

            $credentials = [
                'minimum_amount' => $request->cod_minimum_amount,
                'maximum_amount' => $request->cod_maximum_amount,
                'instructions' => $request->cod_instructions,
            ];
        }

        $paymentMethod->updateCredentials($credentials);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method credentials updated successfully.');
    }

    public function toggleStatus(PaymentMethodMaster $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        $status = $paymentMethod->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Payment method {$status} successfully.");
    }

    public function destroy(PaymentMethodMaster $paymentMethod)
    {
        // Don't allow deletion of default payment methods
        if (in_array($paymentMethod->slug, ['razorpay', 'payu', 'cod'])) {
            return redirect()->back()
                ->with('error', 'Default payment methods cannot be deleted.');
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    public function showCredentials(PaymentMethodMaster $paymentMethod)
    {
        return view('admin.payment-methods.partials.credentials', compact('paymentMethod'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:payment_methods_master,id',
        ]);

        foreach ($request->order as $index => $id) {
            PaymentMethodMaster::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
