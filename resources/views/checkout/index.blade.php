@extends('layouts.app')

@section('title', 'Checkout - Espee')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Method</h6>
                    </div>
                    <div class="card-body">
                        @foreach($paymentMethods as $method)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_{{ $method->code }}" value="{{ $method->code }}" {{ $loop->first ? 'checked' : '' }}>
                            <label class="form-check-label" for="payment_{{ $method->code }}">
                                <strong>{{ $method->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $method->description }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Coupon Code -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Coupon Code</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Enter coupon code">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-primary w-100" id="apply-coupon">Apply</button>
                            </div>
                        </div>
                        <div id="coupon-message" class="mt-2"></div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Order Notes</h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions for your order..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <small>{{ $item['variant']->product->name }}</small>
                                <br>
                                <small class="text-muted">{{ $item['variant']->color->name }} x{{ $item['quantity'] }}</small>
                            </div>
                            <small>${{ number_format($item['subtotal'], 2) }}</small>
                        </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2" id="discount-row" style="display: none;">
                            <span>Discount:</span>
                            <span id="discount-amount">-$0.00</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>$0.00</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">${{ number_format($total, 2) }}</strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="place-order-btn">
                            <i class="fas fa-credit-card me-2"></i>Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let appliedCoupon = null;

    // Apply coupon
    $('#apply-coupon').on('click', function() {
        const couponCode = $('#coupon_code').val();
        const subtotal = {{ $total }};

        if (!couponCode) {
            showCouponMessage('Please enter a coupon code', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("coupon.validate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: couponCode,
                amount: subtotal
            },
            success: function(response) {
                if (response.success) {
                    appliedCoupon = response.coupon;
                    showCouponMessage('Coupon applied successfully!', 'success');
                    updateOrderSummary();
                } else {
                    showCouponMessage(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showCouponMessage(response.message || 'Error applying coupon', 'error');
            }
        });
    });

    function showCouponMessage(message, type) {
        const messageDiv = $('#coupon-message');
        messageDiv.removeClass('text-success text-danger');
        messageDiv.addClass(type === 'success' ? 'text-success' : 'text-danger');
        messageDiv.text(message);
    }

    function updateOrderSummary() {
        if (appliedCoupon) {
            const discount = appliedCoupon.discount;
            const newTotal = {{ $total }} - discount;

            $('#discount-row').show();
            $('#discount-amount').text('-$' + discount.toFixed(2));
            $('#total').text('$' + newTotal.toFixed(2));
        }
    }

    // Form submission
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (appliedCoupon) {
            formData.append('coupon_code', appliedCoupon.code);
        }

        // Disable submit button
        $('#place-order-btn').prop('disabled', true).text('Processing...');

        $.ajax({
            url: '{{ route("checkout.process") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                $('#place-order-btn').prop('disabled', false).html('<i class="fas fa-credit-card me-2"></i>Place Order');

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    let errorMessage = 'Please fix the following errors:\n';
                    for (const field in xhr.responseJSON.errors) {
                        errorMessage += '- ' + xhr.responseJSON.errors[field][0] + '\n';
                    }
                    alert(errorMessage);
                } else {
                    alert('Error processing order. Please try again.');
                }
            }
        });
    });
});
</script>
@endpush
