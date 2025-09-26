<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\TaxMaster;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        $totalTax = 0;
        $total = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::with('product', 'color', 'images')->find($id);
            if ($variant) {
                $itemSubtotal = $variant->price * $item['quantity'];

                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal
                ];

                $subtotal += $itemSubtotal;
            }
        }

        $total = $subtotal;

        return view('cart.index', compact('cartItems', 'subtotal', 'total'));
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
            'message' => 'Product added to cart successfully! 🛒',
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

        $cart = session()->get('cart', []);
        $totalSubtotal = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::with('product')->find($id);
            if ($variant) {
                $itemSubtotal = $variant->price * $item['quantity'];
                $totalSubtotal += $itemSubtotal;
            }
        }

        $total = $totalSubtotal;

        return response()->json([
            'success' => true,
            'subtotal' => number_format($totalSubtotal, 2),
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

        $totalSubtotal = 0;

        foreach ($cart as $id => $item) {
            $variant = ProductVariant::with('product')->find($id);
            if ($variant) {
                $itemSubtotal = $variant->price * $item['quantity'];
                $totalSubtotal += $itemSubtotal;
            }
        }

        $total = $totalSubtotal;

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'subtotal' => number_format($totalSubtotal, 2),
            'total' => number_format($total, 2)
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
            'cart_count' => 0,
            'subtotal' => '0.00',
            'total' => '0.00'
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = count($cart);

        return response()->json([
            'count' => $count
        ]);
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
