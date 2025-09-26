@extends('layouts.app')

@section('title', 'Shopping Cart - Espee')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Shopping Cart</h2>

    @if(count($cartItems) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center mb-4 cart-item" data-variant-id="{{ $item['variant']->id }}">
                        <div class="col-md-2">
                            <img src="{{ $item['variant']->image_url }}" class="img-fluid rounded" alt="{{ $item['variant']->product->name }}" style="height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-3">
                            <h6 class="mb-1">{{ $item['variant']->product->name }}</h6>
                            <p class="text-muted small mb-1">{{ $item['variant']->product->model_no }}</p>
                            <p class="text-muted small">Color: {{ $item['variant']->color->name }}</p>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Quantity</label>
                            <div class="quantity-container">
                                <input type="number" class="form-control quantity-input" value="{{ $item['quantity'] }}" min="1" max="{{ $item['variant']->stock }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="price-container">
                                <div class="price">₹{{ number_format($item['variant']->price, 2) }}</div>
                                <small class="text-muted">per piece</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="subtotal">₹{{ number_format($item['subtotal'], 2) }}</div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
                <button class="btn btn-outline-danger" id="clear-cart">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">₹{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong id="total">₹{{ number_format($total, 2) }}</strong>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
        <h4>Your cart is empty</h4>
        <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update quantity
    $('.quantity-input').on('change', function() {
        const variantId = $(this).closest('.cart-item').data('variant-id');
        const quantity = $(this).val();

        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                variant_id: variantId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update subtotal for this item
                    const cartItem = $(`.cart-item[data-variant-id="${variantId}"]`);
                    const priceElement = cartItem.find('.price');
                    const price = parseFloat(priceElement.text().replace('₹', '').replace(',', ''));
                    const newSubtotal = price * quantity;
                    cartItem.find('.subtotal').text('₹' + newSubtotal.toFixed(2));

                    // Update totals
                    $('#subtotal').text('₹' + response.total);
                    $('#total').text('₹' + response.total);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast('error', response.message || 'Error updating quantity');
            }
        });
    });

    // Remove item
    $('.remove-item').on('click', function() {
        const variantId = $(this).closest('.cart-item').data('variant-id');

        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                variant_id: variantId
            },
            success: function(response) {
                if (response.success) {
                    // Remove item from DOM
                    $(`.cart-item[data-variant-id="${variantId}"]`).remove();

                    // Update cart count
                    $('#cart-count').text(response.cart_count);

                    // Update totals
                    $('#subtotal').text('₹' + response.total);
                    $('#total').text('₹' + response.total);

                    // Check if cart is empty
                    if (response.cart_count == 0) {
                        location.reload();
                    }
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast('error', response.message || 'Error removing item');
            }
        });
    });

    // Clear cart
    $('#clear-cart').on('click', function() {
        if (confirm('Are you sure you want to clear your cart?')) {
            $.ajax({
                url: '{{ route("cart.clear") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);

                        // Update cart count in navigation
                        $('#cart-count').text(response.cart_count);

                        // Update totals
                        $('#subtotal').text('₹' + response.total);
                        $('#total').text('₹' + response.total);

                        // Redirect to cart page to show empty state
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast('error', response.message || 'Error clearing cart');
                }
            });
        }
    });

    // showToast function is now available globally from app layout
});
</script>

<style>
.quantity-container {
    position: relative;
}

.quantity-input {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
    width: 100%;
    text-align: center;
    font-size: 14px;
}

.quantity-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Ensure no currency formatting is applied to quantity input */
.quantity-input::before,
.quantity-input::after {
    content: none !important;
}

/* Clear any potential currency symbols */
.quantity-container::before,
.quantity-container::after {
    display: none !important;
}

.price-container {
    text-align: left;
    padding-left: 10px;
}

.cart-item .col-md-2 {
    margin-bottom: 10px;
}

/* Ensure proper column separation */
.cart-item [class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
}
</style>
@endpush
