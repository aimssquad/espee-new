@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                <h1 class="mt-3">Thank You for Your Order!</h1>
                <p class="lead text-muted">Your order has been successfully placed.</p>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Number:</strong> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
                            <p class="mb-1"><strong>Status:</strong> {!! $order->status_badge !!}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                            <p class="mb-1"><strong>Payment Method:</strong> Cash on Delivery</p>
                            <p class="mb-1"><strong>Estimated Delivery:</strong> {{ now()->addDays(5)->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Shipping Information</h5>
                    <hr>
                    <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                    <p class="mb-1">{{ $order->email }}</p>
                    <p class="mb-1">{{ $order->phone }}</p>
                    <p class="mb-0">{{ nl2br(e($order->address)) }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Items</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->productVariant->image_url }}" 
                                                 alt="{{ $item->productVariant->product->name }}"
                                                 class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $item->productVariant->product->name }}</h6>
                                                <small class="text-muted">
                                                    Color: {{ $item->productVariant->color->name }}<br>
                                                    SKU: {{ $item->productVariant->sku }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ $item->quantity }}</td>
                                    <td class="text-end align-middle">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-end align-middle">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">What's Next?</h5>
                    <hr>
                    <ul class="mb-0">
                        <li>You will receive an order confirmation email shortly.</li>
                        <li>We will notify you when your order is shipped.</li>
                        <li>You can track your order status by contacting our customer service.</li>
                        <li>If you have any questions, please don't hesitate to contact us.</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-dark">
                    Continue Shopping
                </a>
                <button onclick="window.print()" class="btn btn-outline-dark ms-2">
                    <i class="fas fa-print me-2"></i>Print Order
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .footer, .btn {
        display: none !important;
    }
}
</style>
@endsection