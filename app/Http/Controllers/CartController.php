<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
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

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

        // Check stock
        if ($variant->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ], 400);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$variant->id])) {
            // Check if adding more exceeds stock
            if ($variant->stock < ($cart[$variant->id]['quantity'] + $request->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.'
                ], 400);
            }
            $cart[$variant->id]['quantity'] += $request->quantity;
        } else {
            $cart[$variant->id] = [
                'quantity' => $request->quantity,
                'added_at' => now()
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => count($cart)
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        // Check stock
        if ($variant->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ], 400);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        $subtotal = $variant->price * $request->quantity;
        $total = $this->calculateTotal();

        return response()->json([
            'success' => true,
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2)
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'variant_id' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->variant_id])) {
            unset($cart[$request->variant_id]);
            session()->put('cart', $cart);
        }

        $total = $this->calculateTotal();

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'total' => number_format($total, 2)
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }

    private function calculateTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::find($id);
            if ($variant) {
                $total += $variant->price * $item['quantity'];
            }
        }

        return $total;
    }
}