@extends('layouts.user-panel')

@section('title', 'Order Details - ' . $order->order_number)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('user-panel.orders') }}">My Orders</a>
</li>
<li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
@endsection

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-receipt me-2"></i>Order Details</h2>
    <a href="{{ route('user-panel.orders') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Orders
    </a>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Order Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Order Number:</strong> {{ $order->order_number }}
                    </div>
                    <div class="col-md-6">
                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="order-status status-{{ $order->status }} ms-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Payment Status:</strong>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} ms-2">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                @if($order->tracking_number)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tracking Number:</strong>
                        <code class="ms-2">{{ $order->tracking_number }}</code>
                    </div>
                    @if($order->shipped_at)
                    <div class="col-md-6">
                        <strong>Shipped On:</strong>
                        <span class="ms-2">{{ $order->shipped_at->format('M d, Y H:i A') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}
                    </div>
                    <div class="col-md-6">
                        <strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}
                    </div>
                </div>

                @if($order->notes)
                <div class="mb-3">
                    <strong>Order Notes:</strong>
                    <p class="text-muted mt-1">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Tracking -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Order Tracking</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">{{ $order->tracking_status }}</span>
                        <span class="text-muted">{{ $order->tracking_progress }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary"
                             role="progressbar"
                             style="width: {{ $order->tracking_progress }}%"
                             aria-valuenow="{{ $order->tracking_progress }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-3">
                        <div class="tracking-step {{ $order->tracking_progress >= 25 ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <div class="small">Order Placed</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="tracking-step {{ $order->tracking_progress >= 50 ? 'active' : '' }}">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div class="small">Confirmed</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="tracking-step {{ $order->tracking_progress >= 75 ? 'active' : '' }}">
                            <i class="fas fa-shipping-fast fa-2x mb-2"></i>
                            <div class="small">Shipped</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="tracking-step {{ $order->tracking_progress >= 100 ? 'active' : '' }}">
                            <i class="fas fa-gift fa-2x mb-2"></i>
                            <div class="small">Delivered</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Order Items</h6>
            </div>
            <div class="card-body">
                @foreach($order->items as $item)
                <div class="row align-items-center mb-3 pb-3 border-bottom">
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
                            <img src="{{ $imageUrl }}"
                                 class="img-fluid rounded"
                                 alt="{{ $product->name }}"
                                 style="height: 80px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/80x80/000000/FFFFFF?text={{ urlencode(substr($product->name, 0, 10)) }}"
                                 class="img-fluid rounded"
                                 alt="{{ $product->name }}"
                                 style="height: 80px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-1">{{ $item->productVariant->product->name }}</h6>
                        <p class="text-muted small mb-1">{{ $item->productVariant->product->model_no }}</p>
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
    </div>

    <!-- Shipping Address & Order Summary -->
    <div class="col-lg-4 mb-4">
        <!-- Shipping Address -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Shipping Address</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-2">{{ $order->address }}</div>
                @if($order->city)
                <div class="mb-2">{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</div>
                @endif
                <div class="mb-0">
                    <i class="fas fa-phone me-1"></i>{{ $order->phone }}
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>

                @if($order->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <span class="text-success">-₹{{ number_format($order->discount_amount, 2) }}</span>
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
                @if($order->cgst_amount > 0 || $order->sgst_amount > 0 || $order->igst_amount > 0)
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">CGST ({{ number_format($order->cgst_rate ?? 0, 2) }}%):</small>
                    <small class="text-muted">₹{{ number_format($order->cgst_amount ?? 0, 2) }}</small>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">SGST ({{ number_format($order->sgst_rate ?? 0, 2) }}%):</small>
                    <small class="text-muted">₹{{ number_format($order->sgst_amount ?? 0, 2) }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">IGST ({{ number_format($order->igst_rate ?? 0, 2) }}%):</small>
                    <small class="text-muted">₹{{ number_format($order->igst_amount ?? 0, 2) }}</small>
                </div>
                @endif
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
        </div>

        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Order
                    </button>
                    <a href="{{ route('user-panel.orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.tracking-step {
    color: #6c757d;
    transition: all 0.3s ease;
}

.tracking-step.active {
    color: #007bff;
}

.tracking-step i {
    transition: all 0.3s ease;
}

.tracking-step.active i {
    color: #007bff;
    transform: scale(1.1);
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

@media print {
    .btn, .card-header, .navbar, .sidebar {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    .container-fluid {
        padding: 0 !important;
    }
}
</style>
@endpush
