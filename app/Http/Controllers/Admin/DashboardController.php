<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_skus' => ProductVariant::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
            'low_stock_variants' => ProductVariant::where('stock', '<', 10)->with('product', 'color')->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}