<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            $orderItems = [];

            foreach ($cart as $variantId => $item) {
                $variant = ProductVariant::lockForUpdate()->find($variantId);
                
                if (!$variant || $variant->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Some products are out of stock.');
                }

                $subtotal = $variant->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price
                ];
            }

            // Create order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'total_amount' => $total,
                'status' => 'pending'
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