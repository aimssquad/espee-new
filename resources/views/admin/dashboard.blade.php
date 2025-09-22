@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <h1 class="h3 mb-0">Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-glasses"></i>
            </div>
            <h3 class="mb-1">{{ $stats['total_products'] }}</h3>
            <p class="text-muted mb-0">Total Products</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-barcode"></i>
            </div>
            <h3 class="mb-1">{{ $stats['total_skus'] }}</h3>
            <p class="text-muted mb-0">Total SKUs</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="mb-1">{{ $stats['total_orders'] }}</h3>
            <p class="text-muted mb-0">Total Orders</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="mb-1">{{ $stats['pending_orders'] }}</h3>
            <p class="text-muted mb-0">Pending Orders</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_orders']->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_orders'] as $order)
                            <tr>
                                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>{!! $order->status_badge !!}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-muted mb-0">No orders yet</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Low Stock Alerts</h5>
            </div>
            <div class="card-body">
                @if($stats['low_stock_variants']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['low_stock_variants'] as $variant)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $variant->product->name }}</h6>
                                <small class="text-muted">
                                    {{ $variant->color->name }} - {{ $variant->sku }}
                                </small>
                            </div>
                            <span class="badge bg-danger">{{ $variant->stock }} left</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-muted mb-0">All products are well stocked</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection