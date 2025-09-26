@extends('layouts.user-panel')

@section('title', 'Dashboard - My Account')

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    <small class="text-muted">Welcome back, {{ $user->name }}!</small>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="fas fa-shopping-bag fa-2x mb-3"></i>
                <div class="stats-number">{{ $orderStats['total_orders'] }}</div>
                <div>Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <div class="stats-number">{{ $orderStats['pending_orders'] }}</div>
                <div>Pending Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <div class="stats-number">{{ $orderStats['completed_orders'] }}</div>
                <div>Completed Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="fas fa-rupee-sign fa-2x mb-3"></i>
                <div class="stats-number">₹{{ number_format($orderStats['total_spent'], 0) }}</div>
                <div>Total Spent</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Orders</h6>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="order-status status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        <a href="{{ route('user-panel.order-details', $order) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('user-panel.orders') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No orders yet</h5>
                        <p class="text-muted">Start shopping to see your orders here!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('user-panel.orders') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i>View All Orders
                    </a>
                    <a href="{{ route('user-panel.addresses') }}" class="btn btn-outline-success">
                        <i class="fas fa-map-marker-alt me-2"></i>Manage Addresses
                    </a>
                    <a href="{{ route('user-panel.profile') }}" class="btn btn-outline-info">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Account Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Name:</strong> {{ $user->name }}
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
                <div class="mb-2">
                    <strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}
                </div>
                <div class="mb-0">
                    <strong>Member since:</strong> {{ $user->created_at->format('M Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
