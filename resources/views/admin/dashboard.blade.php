@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-box fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">{{ $stats['total_products'] }}</h5>
                    <p class="card-text">Total Products</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-tags fa-2x text-success mb-2"></i>
                    <h5 class="card-title">{{ $stats['total_skus'] }}</h5>
                    <p class="card-text">Total SKUs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x text-info mb-2"></i>
                    <h5 class="card-title">{{ $stats['total_orders'] }}</h5>
                    <p class="card-text">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5 class="card-title">{{ $stats['pending_orders'] }}</h5>
                    <p class="card-text">Pending Orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">{{ $visitorStats['total_visitors'] }}</h5>
                    <p class="card-text">Total Visitors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                    <h5 class="card-title">{{ $visitorStats['unique_visitors'] }}</h5>
                    <p class="card-text">Unique Visitors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-rupee-sign fa-2x text-info mb-2"></i>
                    <h5 class="card-title">₹{{ number_format($orderStats['total_revenue'], 2) }}</h5>
                    <p class="card-text">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                    <h5 class="card-title">₹{{ number_format($orderStats['average_order_value'], 2) }}</h5>
                    <p class="card-text">Avg Order Value</p>
                </div>
            </div>
        </div>
    </div>

     <!-- Quick Actions -->
     <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>Categories
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-tags me-2"></i>Subcategories
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-box me-2"></i>Products
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.excel-upload.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-excel me-2"></i>Excel Upload
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Orders
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-ticket-alt me-2"></i>Coupons
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-palette me-2"></i>Colors
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.shapes.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shapes me-2"></i>Shapes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-5">
        <!-- Orders Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Orders Trend (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Visitors Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Visitors Trend (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="visitorsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Revenue Trend (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($stats['recent_orders']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_orders'] as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Low Stock Alert</h6>
                </div>
                <div class="card-body">
                    @if($stats['low_stock_variants']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['low_stock_variants'] as $variant)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-bold">{{ $variant->product->name }}</small>
                                <br>
                                <small class="text-muted">{{ $variant->color->name }}</small>
                            </div>
                            <span class="badge bg-danger">{{ $variant->stock }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">All products are well stocked.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($orderChartData, 'date')) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode(array_column($orderChartData, 'orders')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Visitors Chart
    const visitorsCtx = document.getElementById('visitorsChart').getContext('2d');
    const visitorsChart = new Chart(visitorsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($visitorChartData, 'date')) !!},
            datasets: [{
                label: 'Total Visitors',
                data: {!! json_encode(array_column($visitorChartData, 'visitors')) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }, {
                label: 'Unique Visitors',
                data: {!! json_encode(array_column($visitorChartData, 'unique_visitors')) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($revenueChartData, 'date')) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode(array_column($revenueChartData, 'revenue')) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.8)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
