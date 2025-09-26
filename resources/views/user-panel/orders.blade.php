@extends('layouts.user-panel')

@section('title', 'My Orders - My Account')

@section('breadcrumb')
<li class="breadcrumb-item active">My Orders</li>
@endsection

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-shopping-bag me-2"></i>My Orders</h2>
    <a href="{{ route('products.index') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
    </a>
</div>

@if($orders->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $order->items->count() }} item(s)</span>
                            </td>
                            <td>
                                <span class="order-status status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                            </td>
                            <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                            <td>
                                <a href="{{ route('user-panel.order-details', $order) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">No orders found</h4>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
            </a>
        </div>
    </div>
@endif
@endsection
