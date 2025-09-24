@extends('layouts.app')

@section('title', 'Order Confirmation - Espee')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                <h2>Order Confirmed!</h2>
                <p class="text-muted">Thank you for your order. We'll send you a confirmation email shortly.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Order Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong> {{ $order->order_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer:</strong> {{ $order->customer_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $order->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $order->phone }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Shipping Address:</strong>
                        <p class="mb-0">{{ $order->address }}</p>
                        @if($order->city)
                            <p class="mb-0">{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="row align-items-center mb-3">
                        <div class="col-md-2">
                            @if($item->productVariant->image)
                                <img src="{{ $item->productVariant->image_url }}" class="img-fluid rounded" alt="{{ $item->productVariant->product->name }}" style="height: 60px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/60x60/000000/FFFFFF?text={{ urlencode($item->productVariant->product->name) }}" class="img-fluid rounded" alt="{{ $item->productVariant->product->name }}" style="height: 60px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-1">{{ $item->productVariant->product->name }}</h6>
                            <p class="text-muted small mb-0">{{ $item->productVariant->product->model_no }}</p>
                            <p class="text-muted small">Color: {{ $item->productVariant->color->name }}</p>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">Qty: {{ $item->quantity }}</span>
                        </div>
                        <div class="col-md-2">
                            <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span>-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>$0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <p class="mb-2"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                <p class="mb-0"><strong>Payment Status:</strong>
                                    <span class="badge bg-warning">{{ ucfirst($order->payment_status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('products.index') }}" class="btn btn-primary me-3">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
