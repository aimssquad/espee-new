<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\VisitorAnalytics;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Basic stats
        $stats = [
            'total_products' => Product::count(),
            'total_skus' => ProductVariant::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
            'low_stock_variants' => ProductVariant::where('stock', '<', 10)->with('product', 'color')->get()
        ];

        // Visitor analytics
        $visitorStats = [
            'total_visitors' => VisitorAnalytics::byDateRange($startDate, $endDate)->count(),
            'unique_visitors' => VisitorAnalytics::byDateRange($startDate, $endDate)->uniqueVisitors()->count(),
            'today_visitors' => VisitorAnalytics::today()->count(),
            'today_unique_visitors' => VisitorAnalytics::today()->uniqueVisitors()->count(),
            'this_week_visitors' => VisitorAnalytics::thisWeek()->count(),
            'this_month_visitors' => VisitorAnalytics::thisMonth()->count()
        ];

        // Order analytics
        $orderStats = [
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->sum('total_amount'),
            'total_orders_count' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count(),
            'average_order_value' => 0
        ];

        if ($orderStats['total_orders_count'] > 0) {
            $orderStats['average_order_value'] = $orderStats['total_revenue'] / $orderStats['total_orders_count'];
        }

        // Chart data for orders (last 30 days)
        $orderChartData = $this->getOrderChartData();

        // Chart data for visitors (last 30 days)
        $visitorChartData = $this->getVisitorChartData();

        // Revenue chart data
        $revenueChartData = $this->getRevenueChartData();

        return view('admin.dashboard', compact(
            'stats',
            'visitorStats',
            'orderStats',
            'orderChartData',
            'visitorChartData',
            'revenueChartData',
            'startDate',
            'endDate'
        ));
    }

    private function getOrderChartData()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $orders = Order::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date,
                'orders' => $orders
            ];
        }
        return $data;
    }

    private function getVisitorChartData()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $visitors = VisitorAnalytics::where('visit_date', $date)->count();
            $uniqueVisitors = VisitorAnalytics::where('visit_date', $date)->uniqueVisitors()->count();
            $data[] = [
                'date' => $date,
                'visitors' => $visitors,
                'unique_visitors' => $uniqueVisitors
            ];
        }
        return $data;
    }

    private function getRevenueChartData()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Order::whereDate('created_at', $date)->sum('total_amount');
            $data[] = [
                'date' => $date,
                'revenue' => $revenue
            ];
        }
        return $data;
    }
}
