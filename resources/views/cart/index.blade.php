@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>

    @if(count($cartItems) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr class="cart-item" data-variant-id="{{ $item['variant']->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item['variant']->image_url }}" 
                                                 alt="{{ $item['variant']->product->name }}"
                                                 class="me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $item['variant']->product->name }}</h6>
                                                <small class="text-muted">
                                                    Color: {{ $item['variant']->color->name }}<br>
                                                    SKU: {{ $item['variant']->sku }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        $<span class="item-price">{{ number_format($item['variant']->price, 2) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-dark qty-decrease">-</button>
                                            <input type="number" class="form-control mx-2 text-center item-quantity" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   max="{{ $item['variant']->stock }}"
                                                   style="width: 60px;">
                                            <button type="button" class="btn btn-sm btn-outline-dark qty-increase">+</button>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        $<span class="item-subtotal">{{ number_format($item['subtotal'], 2) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Clear Cart
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>$<span id="cart-subtotal">{{ number_format($total, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h6 class="mb-0">Total</h6>
                        <h6 class="mb-0">$<span id="cart-total">{{ number_format($total, 2) }}</span></h6>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-dark w-100">
                        Proceed to Checkout
                    </a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Why Shop With Us?</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Free Shipping</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Secure Payment</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>30-Day Returns</li>
                        <li><i class="fas fa-check text-success me-2"></i>1 Year Warranty</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h3>Your cart is empty</h3>
        <p class="text-muted mb-4">Add some products to your cart to see them here.</p>
        <a href="{{ route('products.index') }}" class="btn btn-dark">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update quantity
    function updateQuantity(row, newQuantity) {
        const variantId = row.data('variant-id');
        const maxStock = row.find('.item-quantity').attr('max');
        
        if (newQuantity < 1) {
            newQuantity = 1;
        } else if (newQuantity > maxStock) {
            newQuantity = maxStock;
        }
        
        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'POST',
            data: {
                variant_id: variantId,
                quantity: newQuantity
            },
            success: function(response) {
                if (response.success) {
                    row.find('.item-quantity').val(newQuantity);
                    row.find('.item-subtotal').text(response.subtotal);
                    $('#cart-subtotal').text(response.total);
                    $('#cart-total').text(response.total);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'An error occurred', 'error');
            }
        });
    }
    
    // Quantity decrease
    $('.qty-decrease').on('click', function() {
        const row = $(this).closest('tr');
        const currentQty = parseInt(row.find('.item-quantity').val());
        updateQuantity(row, currentQty - 1);
    });
    
    // Quantity increase
    $('.qty-increase').on('click', function() {
        const row = $(this).closest('tr');
        const currentQty = parseInt(row.find('.item-quantity').val());
        updateQuantity(row, currentQty + 1);
    });
    
    // Manual quantity change
    $('.item-quantity').on('change', function() {
        const row = $(this).closest('tr');
        const newQty = parseInt($(this).val());
        updateQuantity(row, newQty);
    });
    
    // Remove item
    $('.remove-item').on('click', function() {
        if (!confirm('Are you sure you want to remove this item?')) {
            return;
        }
        
        const row = $(this).closest('tr');
        const variantId = row.data('variant-id');
        
        $.ajax({
            url: '{{ route("cart.remove") }}',
            type: 'POST',
            data: {
                variant_id: variantId
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                        
                        if ($('.cart-item').length === 0) {
                            window.location.reload();
                        } else {
                            $('#cart-subtotal').text(response.total);
                            $('#cart-total').text(response.total);
                            updateCartCount(response.cart_count);
                        }
                    });
                }
            }
        });
    });
});
</script>
@endpush