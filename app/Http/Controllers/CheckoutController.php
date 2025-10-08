<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\PaymentMethodMaster;
use App\Models\TaxMaster;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;
        $totalTax = 0;
        $total = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::with('product', 'color')->find($id);
            if ($variant) {
                $inclusivePrice = $variant->price; // This is already inclusive price
                $itemSubtotal = $inclusivePrice * $item['quantity'];

                // Calculate tax from inclusive price for display purposes
                $taxRate = TaxMaster::getTaxForProduct($variant->product);
                $taxRatePercent = $taxRate ? $taxRate->tax_rate : 18;
                $basePrice = $inclusivePrice / (1 + ($taxRatePercent / 100));
                $taxAmount = $inclusivePrice - $basePrice;
                $itemTaxAmount = $taxAmount * $item['quantity'];

                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal
                ];

                $subtotal += $itemSubtotal;
                $totalTax += $itemTaxAmount;
            }
        }

        $total = $subtotal;
        $paymentMethods = PaymentMethodMaster::getActivePaymentMethodsForOrder($total);

        // Get available coupons for this order amount
        $availableCoupons = Coupon::where('is_active', true)
            ->where(function($query) use ($total) {
                $query->whereNull('minimum_amount')
                      ->orWhere('minimum_amount', '<=', $total);
            })
            ->where(function($query) {
                $query->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('used_count < usage_limit');
            })
            ->orderBy('value', 'desc')
            ->get();

        // Get user's saved addresses if logged in
        $userAddresses = [];
        $defaultAddress = null;
        if (Auth::check()) {
            $userAddresses = UserAddress::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
            $defaultAddress = UserAddress::where('user_id', Auth::id())->where('is_default', true)->first();
        }

        return view('checkout.index', compact('cartItems', 'subtotal', 'totalTax', 'total', 'paymentMethods', 'availableCoupons', 'userAddresses', 'defaultAddress'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'payment_method' => 'required|string',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'saved_address_id' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $variantId => $item) {
                $variant = ProductVariant::lockForUpdate()->find($variantId);

                if (!$variant || $variant->stock < $item['quantity']) {
                    DB::rollBack();
                    if (request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Some products are out of stock.'
                        ], 400);
                    }
                    return redirect()->route('cart.index')
                        ->with('error', 'Some products are out of stock.');
                }

                $itemSubtotal = $variant->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price
                ];
            }

            // Apply coupon if provided
            $discountAmount = 0;
            $couponId = null;

            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])->first();
                if ($coupon && $coupon->isValid()) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                }
            }

            $totalAmount = $subtotal - $discountAmount;

            // Calculate weighted average tax rate based on base prices
            $totalBasePrice = 0;
            $weightedTaxRate = 0;

            foreach ($cart as $id => $item) {
                $variant = ProductVariant::with('product', 'color')->find($id);
                if ($variant) {
                    $taxRate = TaxMaster::getTaxForProduct($variant->product);
                    $taxRatePercent = $taxRate ? $taxRate->tax_rate : 18;

                    $inclusivePrice = $variant->price;
                    $basePrice = $inclusivePrice / (1 + ($taxRatePercent / 100));
                    $itemBasePrice = $basePrice * $item['quantity'];

                    $totalBasePrice += $itemBasePrice;
                    $weightedTaxRate += $taxRatePercent * $itemBasePrice;
                }
            }

            $averageTaxRate = $totalBasePrice > 0 ? $weightedTaxRate / $totalBasePrice : 0;

            // Calculate CGST/SGST/IGST based on customer state
            $customerState = $validated['state'] ?? null;
            $isSameState = $this->isSameState($customerState);

            $cgstAmount = 0;
            $sgstAmount = 0;
            $igstAmount = 0;
            $cgstRate = 0;
            $sgstRate = 0;
            $igstRate = 0;

            if ($isSameState) {
                // Same state - use CGST + SGST
                $cgstAmount = $totalTax / 2;
                $sgstAmount = $totalTax / 2;
                $cgstRate = $averageTaxRate / 2;
                $sgstRate = $averageTaxRate / 2;
            } else {
                // Different state - use IGST
                $igstAmount = $totalTax;
                $igstRate = $averageTaxRate;
            }

            // Handle saved address selection
            $shippingAddress = [
                'name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode']
            ];

            // If a saved address was selected, get the full address details
            if (isset($validated['saved_address_id']) && $validated['saved_address_id'] !== 'new' && Auth::check()) {
                $savedAddress = UserAddress::where('user_id', Auth::id())
                    ->where('id', $validated['saved_address_id'])
                    ->first();

                if ($savedAddress) {
                    $shippingAddress = [
                        'name' => $savedAddress->name,
                        'phone' => $savedAddress->phone,
                        'address' => $savedAddress->address,
                        'city' => $savedAddress->city,
                        'state' => $savedAddress->state,
                        'pincode' => $savedAddress->pincode
                    ];
                }
            }

            // Generate order number
            $orderNumber = 'ESP-' . strtoupper(Str::random(8));

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::check() ? Auth::id() : null,
                'customer_name' => $shippingAddress['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $shippingAddress['phone'],
                'address' => $shippingAddress['address'],
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'],
                'pincode' => $shippingAddress['pincode'],
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'tax_rate' => $averageTaxRate,
                'tax_type' => 'GST',
                'cgst_rate' => $cgstRate,
                'sgst_rate' => $sgstRate,
                'igst_rate' => $igstRate,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
                'igst_amount' => $igstAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'coupon_id' => $couponId,
                'status' => 'pending',
                'notes' => $validated['notes']
            ]);

            // Create order items and update stock
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item['variant']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Decrease stock
                $item['variant']->decrement('stock', $item['quantity']);
            }

            // Increment coupon usage if applied
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                $coupon->incrementUsage();
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'redirect' => route('checkout.success', $order)
                ]);
            }

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Order creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $validated,
                'cart' => $cart
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('cart.index')
                ->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        $order->load('items.productVariant.product', 'items.productVariant.color', 'items.productVariant.images');

        return view('checkout.success', compact('order'));
    }

    private function isSameState($customerState)
    {
        // Company is based in West Bengal
        $companyState = 'West Bengal';

        if (!$customerState) {
            return true; // Default to same state if not specified
        }

        return strtolower($customerState) === strtolower($companyState);
    }
}
