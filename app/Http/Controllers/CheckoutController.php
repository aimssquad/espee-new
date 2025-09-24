<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
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
        $total = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::with('product', 'color')->find($id);
            if ($variant) {
                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'subtotal' => $variant->price * $item['quantity']
                ];
                $total += $variant->price * $item['quantity'];
            }
        }

        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('checkout.index', compact('cartItems', 'total', 'paymentMethods'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'payment_method' => 'required|string',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
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

            // Generate order number
            $orderNumber = 'ESP-' . strtoupper(Str::random(8));

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
                'subtotal' => $subtotal,
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

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function success(Order $order)
    {
        $order->load('items.productVariant.product', 'items.productVariant.color');

        return view('checkout.success', compact('order'));
    }
}
