@extends('layouts.app')

@section('title', 'Order Confirmation - Espee')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="success-animation mb-4">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                </div>
                <h2 class="text-success mb-3">🎉 Order Confirmed Successfully!</h2>
                <p class="lead text-muted mb-3">Thank you for choosing Espee! Your order has been placed successfully.</p>
                <div class="alert alert-success d-inline-block">
                    <i class="fas fa-envelope me-2"></i>
                    <strong>Confirmation email</strong> will be sent to <strong>{{ $order->email }}</strong>
                </div>
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
                            @php
                                $product = $item->productVariant->product;
                                $imageUrl = null;

                                // Try to get image from variant first
                                if($item->productVariant->images && $item->productVariant->images->count() > 0) {
                                    $imageUrl = $item->productVariant->images->first()->image_url;
                                }
                                // Fallback to product's main image
                                elseif($product->main_image) {
                                    $imageUrl = $product->main_image;
                                }
                            @endphp

                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" class="img-fluid rounded" alt="{{ $product->name }}" style="height: 60px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/60x60/000000/FFFFFF?text={{ urlencode(substr($product->name, 0, 10)) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="height: 60px; object-fit: cover;">
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
                            <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
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
                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            @if($order->tax_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax ({{ $order->tax_type ?? 'GST' }}):</span>
                                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Tax Rate:</small>
                                <small class="text-muted">{{ number_format($order->tax_rate, 2) }}%</small>
                            </div>
                            @else
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>₹0.00</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
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
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-success btn-lg w-100" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Order
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
                        <ul class="list-unstyled mb-0 text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Order confirmation email sent</li>
                            <li><i class="fas fa-truck text-warning me-2"></i>Order processing (1-2 business days)</li>
                            <li><i class="fas fa-shipping-fast text-info me-2"></i>Shipping notification with tracking</li>
                            <li><i class="fas fa-gift text-primary me-2"></i>Delivery to your doorstep</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-animation {
    animation: bounceIn 1s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 0.5rem;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border: 1px solid #bee5eb;
}

@media print {
    .btn, .alert {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
}
</style>
@endpush
