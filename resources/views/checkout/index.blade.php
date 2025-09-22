@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Billing Information</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="address" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Payment Information</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This is a demo store. No actual payment will be processed.
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                            <label class="form-check-label" for="payment_cod">
                                Cash on Delivery
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                            <label class="form-check-label" for="payment_card">
                                Credit/Debit Card (Demo)
                            </label>
                        </div>
                        
                        <div id="card-details" class="mt-3" style="display: none;">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <small>{{ $item['variant']->product->name }}</small><br>
                                <small class="text-muted">{{ $item['variant']->color->name }} × {{ $item['quantity'] }}</small>
                            </div>
                            <span>${{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                        @endforeach
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h6 class="mb-0">Total</h6>
                            <h6 class="mb-0">${{ number_format($total, 2) }}</h6>
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100" id="place-order-btn">
                            <i class="fas fa-lock me-2"></i>Place Order
                        </button>
                        
                        <p class="text-center text-muted mt-3 mb-0">
                            <small>Your personal data will be used to process your order and support your experience throughout this website.</small>
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('cart.index') }}" class="text-muted">
                        <i class="fas fa-arrow-left me-2"></i>Return to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle card details
    $('input[name="payment_method"]').on('change', function() {
        if ($(this).val() === 'card') {
            $('#card-details').slideDown();
        } else {
            $('#card-details').slideUp();
        }
    });
    
    // Form submission
    $('#checkout-form').on('submit', function(e) {
        const btn = $('#place-order-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
    });
    
    // Format card number
    $('#card-details input[placeholder*="1234"]').on('input', function() {
        let value = $(this).val().replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        $(this).val(formattedValue);
    });
    
    // Format expiry date
    $('#card-details input[placeholder="MM/YY"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        $(this).val(value);
    });
    
    // Only allow numbers for CVV
    $('#card-details input[placeholder="123"]').on('input', function() {
        $(this).val($(this).val().replace(/\D/g, ''));
    });
});
</script>
@endpush