@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Color</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items ?? [] as $item)
                                <tr>
                                    <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->productVariant->color->name ?? 'N/A' }}</td>
                                    <td><code>{{ $item->productVariant->sku ?? 'N/A' }}</code></td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No items found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Order Notes</h6>
                </div>
                <div class="card-body">
                    <p>{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax ({{ $order->tax_type ?? 'GST' }}):</span>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
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
                    @endif
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span class="text-success">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    @if($order->city)
                        <p><strong>City:</strong> {{ $order->city }}</p>
                    @endif
                    @if($order->state)
                        <p><strong>State:</strong> {{ $order->state }}</p>
                    @endif
                    @if($order->pincode)
                        <p><strong>Pincode:</strong> {{ $order->pincode }}</p>
                    @endif
                </div>
            </div>

            <!-- Order Status -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Order Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
