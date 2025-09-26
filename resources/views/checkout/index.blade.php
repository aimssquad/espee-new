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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
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
                        @if(Auth::check() && $userAddresses->count() > 0)
                        <!-- Saved Addresses Selection -->
                        <div class="mb-4">
                            <label class="form-label">Choose from saved addresses</label>
                            <div class="row" id="saved-addresses">
                                @foreach($userAddresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="card address-option"
                                         data-address-id="{{ $address->id }}"
                                         data-name="{{ $address->name }}"
                                         data-phone="{{ $address->phone }}"
                                         data-email="{{ $address->email ?? '' }}"
                                         data-address="{{ $address->address }}"
                                         data-city="{{ $address->city }}"
                                         data-state="{{ $address->state }}"
                                         data-pincode="{{ $address->pincode }}">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="saved_address_id" value="{{ $address->id }}" id="address_{{ $address->id }}">
                                                <label class="form-check-label w-100" for="address_{{ $address->id }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $address->name }}</strong>
                                                            @if($address->is_default)
                                                                <span class="badge bg-success ms-2">Default</span>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">{{ $address->phone }}</small>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small class="text-muted">{{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</small>
                                                        @if($address->email)
                                                            <br><small class="text-muted">{{ $address->email }}</small>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-md-6 mb-3">
                                    <div class="card address-option" data-address-id="new">
                                        <div class="card-body p-3 text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="saved_address_id" value="new" id="address_new" checked>
                                                <label class="form-check-label w-100" for="address_new">
                                                    <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                                                    <div>Add New Address</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Address Form -->
                        <div id="address-form" @if(Auth::check() && $userAddresses->count() > 0) style="display: none;" @endif>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="pincode" name="pincode" maxlength="6" pattern="[0-9]{6}" required>
                                    <button class="btn btn-outline-secondary" type="button" id="lookup-pincode">
                                        <i class="fas fa-search" id="lookup-icon"></i>
                                    </button>
                                </div>
                                <div id="pincode-lookup-message" class="form-text"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="city" name="city" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" id="edit-city" title="Edit manually">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="state" name="state" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" id="edit-state" title="Edit manually">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div> <!-- End address-form -->
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Method</h6>
                    </div>
                    <div class="card-body">
                        @if($paymentMethods->count() > 0)
                            @foreach($paymentMethods as $method)
                        <div class="form-check mb-3 payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_{{ $method->slug }}" value="{{ $method->slug }}" {{ $loop->first ? 'checked' : '' }}>
                            <label class="form-check-label d-flex align-items-center" for="payment_{{ $method->slug }}">
                                @if($method->icon)
                                    <i class="{{ $method->icon }} me-3 text-primary" style="font-size: 1.2rem;"></i>
                                @endif
                                <div>
                                    <strong>{{ $method->display_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $method->description }}</small>
                                    @if($method->isCOD() && ($method->cod_minimum_amount || $method->cod_maximum_amount))
                                        <br>
                                        <small class="text-info">
                                            @if($method->cod_minimum_amount)
                                                Min: ₹{{ number_format($method->cod_minimum_amount, 2) }}
                                            @endif
                                            @if($method->cod_maximum_amount)
                                                @if($method->cod_minimum_amount) - @endif
                                                Max: ₹{{ number_format($method->cod_maximum_amount, 2) }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </label>
                        </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No payment methods available. Please contact support.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Coupon Code -->
                <div class="card mb-4">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#couponSection" aria-expanded="false" aria-controls="couponSection">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-tag me-2"></i>
                                    Coupon Code
                                    @if($availableCoupons->count() > 0)
                                        <span class="badge bg-primary ms-2">{{ $availableCoupons->count() }} Available</span>
                                    @endif
                                </h6>
                                <div id="applied-coupon-summary" class="small text-success mt-1" style="display: none;">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="applied-coupon-text"></span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down" id="couponToggleIcon"></i>
                        </div>
                    </div>
                    <div class="collapse" id="couponSection">
                        <div class="card-body">
                            @if($availableCoupons->count() > 0)
                                <div class="mb-3">
                                    <h6 class="small text-muted mb-2">Available Coupons:</h6>
                                    <div class="row">
                                        @foreach($availableCoupons as $coupon)
                                            @php
                                                $discount = $coupon->calculateDiscount($total);
                                            @endphp
                                            <div class="col-md-6 mb-2">
                                                <div class="coupon-option p-2 border rounded cursor-pointer"
                                                     data-coupon-code="{{ $coupon->code }}"
                                                     data-coupon-discount="{{ $discount }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong class="text-primary">{{ $coupon->code }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $coupon->name }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge bg-success">
                                                                @if($coupon->type === 'percentage')
                                                                    {{ $coupon->value }}% OFF
                                                                @else
                                                                    ₹{{ $coupon->value }} OFF
                                                                @endif
                                                            </span>
                                                            <br>
                                                            <small class="text-success">Save ₹{{ number_format($discount, 2) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Enter coupon code">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-primary w-100" id="apply-coupon">Apply</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger w-100" id="remove-coupon" style="display: none;">Remove</button>
                                </div>
                            </div>
                            <div id="coupon-message" class="mt-2"></div>
                        </div>
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
                            <small>₹{{ number_format($item['subtotal'], 2) }}</small>
                        </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (Including Tax):</span>
                            <span id="subtotal">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax Breakdown:</span>
                            <span class="text-muted small">₹{{ number_format($totalTax, 2) }} included (GST)</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2" id="discount-row" style="display: none;">
                            <span>Discount:</span>
                            <span id="discount-amount">-₹0.00</span>
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

@push('styles')
<style>
.payment-method-option {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-method-option:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.payment-method-option .form-check-input:checked + .form-check-label {
    color: #007bff;
}

.payment-method-option .form-check-input:checked ~ * {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.payment-method-option .form-check-input {
    margin-top: 0.5rem;
}

.coupon-option {
    border: 2px solid #e9ecef !important;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #fff;
}

.coupon-option:hover {
    border-color: #28a745 !important;
    background-color: #f8fff9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.coupon-option.selected {
    border-color: #28a745 !important;
    background-color: #e8f5e8;
}

.cursor-pointer {
    cursor: pointer;
}

.card-header[data-bs-toggle="collapse"] {
    transition: background-color 0.3s ease;
}

.card-header[data-bs-toggle="collapse"]:hover {
    background-color: #f8f9fa;
}

#couponToggleIcon {
    transition: transform 0.3s ease;
}

#couponToggleIcon.rotated {
    transform: rotate(180deg);
}

#lookup-pincode {
    transition: all 0.3s ease;
}

#lookup-pincode:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

#lookup-pincode:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

#lookup-icon {
    transition: transform 0.3s ease;
}

#lookup-icon.spinning {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.form-control[readonly] {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.input-group .btn {
    border-left: 0;
}

.input-group .form-control:focus + .btn {
    border-color: #86b7fe;
}

/* Address Selection Styles */
.address-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.address-option:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.address-option .form-check-input:checked + .form-check-label {
    color: var(--primary-color);
}

.address-option .form-check-input:checked ~ .card-body {
    background-color: rgba(0, 123, 255, 0.05);
}

.address-option .card-body {
    transition: background-color 0.3s ease;
}

.address-option .form-check-input {
    margin-top: 0.25rem;
}

.address-option .form-check-label {
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let appliedCoupon = null;

    // Handle saved address selection
    $('input[name="saved_address_id"]').change(function() {
        const selectedAddressId = $(this).val();
        const addressForm = $('#address-form');

        if (selectedAddressId === 'new') {
            // Show address form for new address
            addressForm.show();
            // Clear form fields
            $('#address, #pincode, #city, #state').val('');
        } else {
            // Hide address form and populate with selected address
            addressForm.hide();

            // Get address data from the selected card using data attributes
            const selectedCard = $(this).closest('.address-option');

            const addressData = {
                name: selectedCard.data('name') || '',
                phone: selectedCard.data('phone') || '',
                email: selectedCard.data('email') || '',
                address: selectedCard.data('address') || '',
                city: selectedCard.data('city') || '',
                state: selectedCard.data('state') || '',
                pincode: selectedCard.data('pincode') || ''
            };

            // Debug logging
            console.log('Address data extracted:', addressData);

            // Populate form fields
            $('#customer_name').val(addressData.name);
            $('#phone').val(addressData.phone);
            $('#email').val(addressData.email);
            $('#address').val(addressData.address);
            $('#city').val(addressData.city);
            $('#state').val(addressData.state);
            $('#pincode').val(addressData.pincode);

            // Debug: Check if fields are populated
            console.log('Customer name field value:', $('#customer_name').val());
            console.log('Phone field value:', $('#phone').val());
            console.log('Email field value:', $('#email').val());

            // Ensure required fields are not empty
            if (!addressData.name || !addressData.phone) {
                console.error('Required fields missing:', addressData);
                alert('Error: Could not load address data. Please try again or add a new address.');
                return;
            }
        }
    });

    // Handle address option card clicks
    $('.address-option').click(function() {
        const radio = $(this).find('input[type="radio"]');
        radio.prop('checked', true).trigger('change');
    });

    // Debug: Log all address cards on page load
    $(document).ready(function() {
        $('.address-option').each(function() {
            const card = $(this);
            console.log('Address card data:', {
                id: card.data('address-id'),
                name: card.data('name'),
                phone: card.data('phone'),
                email: card.data('email')
            });
        });
    });

    // Handle collapse toggle icon rotation
    $('#couponSection').on('show.bs.collapse', function () {
        $('#couponToggleIcon').addClass('rotated');
    });

    $('#couponSection').on('hide.bs.collapse', function () {
        $('#couponToggleIcon').removeClass('rotated');
    });

    // Pincode lookup functionality
    $('#lookup-pincode').on('click', function() {
        const pincode = $('#pincode').val().trim();

        if (!pincode) {
            showPincodeMessage('Please enter a pincode', 'error');
            return;
        }

        if (!/^[0-9]{6}$/.test(pincode)) {
            showPincodeMessage('Please enter a valid 6-digit pincode', 'error');
            return;
        }

        lookupPincode(pincode);
    });

    // Auto-lookup on pincode input (after 6 digits)
    $('#pincode').on('input', function() {
        const pincode = $(this).val().trim();
        if (pincode.length === 6 && /^[0-9]{6}$/.test(pincode)) {
            lookupPincode(pincode);
        } else if (pincode.length > 0) {
            clearPincodeFields();
        }
    });

    function lookupPincode(pincode) {
        const button = $('#lookup-pincode');
        const icon = $('#lookup-icon');
        const messageDiv = $('#pincode-lookup-message');

        // Show loading state
        button.prop('disabled', true);
        icon.addClass('spinning');
        messageDiv.removeClass('text-success text-danger').addClass('text-info').text('Looking up pincode...');

        // Use multiple APIs for better reliability
        const apis = [
            `https://api.postalpincode.in/pincode/${pincode}`,
            `https://api.zippopotam.us/in/${pincode}`
        ];

        // Try first API (Postal PIN Code API - most reliable for India)
        $.ajax({
            url: apis[0],
            method: 'GET',
            timeout: 10000,
            success: function(response) {
                if (response && response[0] && response[0].Status === 'Success' && response[0].PostOffice) {
                    const postOffice = response[0].PostOffice[0];
                    updateLocationFields(postOffice.District, postOffice.State);
                    showPincodeMessage(`Location found: ${postOffice.District}, ${postOffice.State}`, 'success');
                } else {
                    // Try second API
                    trySecondAPI(apis[1], pincode);
                }
            },
            error: function() {
                // Try second API
                trySecondAPI(apis[1], pincode);
            }
        });
    }

    function trySecondAPI(apiUrl, pincode) {
        $.ajax({
            url: apiUrl,
            method: 'GET',
            timeout: 10000,
            success: function(response) {
                if (response && response.places && response.places.length > 0) {
                    const place = response.places[0];
                    updateLocationFields(place['place name'], place.state);
                    showPincodeMessage(`Location found: ${place['place name']}, ${place.state}`, 'success');
                } else {
                    showPincodeMessage('Pincode not found. Please enter manually.', 'error');
                }
            },
            error: function() {
                showPincodeMessage('Unable to fetch location. Please enter manually.', 'error');
            }
        });
    }

    function updateLocationFields(city, state) {
        $('#city').val(city);
        $('#state').val(state);
    }

    function clearPincodeFields() {
        $('#city').val('');
        $('#state').val('');
        $('#pincode-lookup-message').removeClass('text-success text-danger text-info').text('');
    }

    function showPincodeMessage(message, type) {
        const messageDiv = $('#pincode-lookup-message');
        const button = $('#lookup-pincode');
        const icon = $('#lookup-icon');

        messageDiv.removeClass('text-success text-danger text-info');
        messageDiv.addClass(`text-${type}`);
        messageDiv.text(message);

        // Reset button state
        button.prop('disabled', false);
        icon.removeClass('spinning');
    }

    // Manual edit functionality for city and state
    $('#edit-city').on('click', function() {
        const cityInput = $('#city');
        const editBtn = $(this);

        if (cityInput.prop('readonly')) {
            cityInput.prop('readonly', false).removeClass('form-control').addClass('form-control').focus();
            editBtn.html('<i class="fas fa-check"></i>').removeClass('btn-outline-secondary').addClass('btn-success');
        } else {
            cityInput.prop('readonly', true);
            editBtn.html('<i class="fas fa-edit"></i>').removeClass('btn-success').addClass('btn-outline-secondary');
        }
    });

    $('#edit-state').on('click', function() {
        const stateInput = $('#state');
        const editBtn = $(this);

        if (stateInput.prop('readonly')) {
            stateInput.prop('readonly', false).removeClass('form-control').addClass('form-control').focus();
            editBtn.html('<i class="fas fa-check"></i>').removeClass('btn-outline-secondary').addClass('btn-success');
        } else {
            stateInput.prop('readonly', true);
            editBtn.html('<i class="fas fa-edit"></i>').removeClass('btn-success').addClass('btn-outline-secondary');
        }
    });

    // Handle clicking on available coupon options
    $('.coupon-option').on('click', function() {
        // Remove previous selection
        $('.coupon-option').removeClass('selected');

        // Add selection to clicked coupon
        $(this).addClass('selected');

        // Fill the coupon code input
        const couponCode = $(this).data('coupon-code');
        $('#coupon_code').val(couponCode);

        // Auto-apply the coupon
        applyCoupon(couponCode);
    });

    // Apply coupon
    $('#apply-coupon').on('click', function() {
        const couponCode = $('#coupon_code').val();
        applyCoupon(couponCode);
    });

    // Remove coupon
    $('#remove-coupon').on('click', function() {
        removeCoupon();
    });

    function applyCoupon(couponCode) {
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
                    $('#remove-coupon').show();
                    $('.coupon-option').removeClass('selected');

                    // Show applied coupon summary in header
                    const couponText = `${response.coupon.code} applied - Save ₹${response.coupon.discount.toFixed(2)}`;
                    $('#applied-coupon-text').text(couponText);
                    $('#applied-coupon-summary').show();

                    // Auto-expand coupon section if collapsed
                    if (!$('#couponSection').hasClass('show')) {
                        $('#couponSection').collapse('show');
                    }
                } else {
                    showCouponMessage(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showCouponMessage(response.message || 'Error applying coupon', 'error');
            }
        });
    }

    function removeCoupon() {
        appliedCoupon = null;
        $('#coupon_code').val('');
        $('#remove-coupon').hide();
        $('.coupon-option').removeClass('selected');
        $('#applied-coupon-summary').hide();
        showCouponMessage('Coupon removed successfully!', 'success');
        updateOrderSummary();
    }

    function showCouponMessage(message, type) {
        const messageDiv = $('#coupon-message');
        messageDiv.removeClass('text-success text-danger');
        messageDiv.addClass(type === 'success' ? 'text-success' : 'text-danger');
        messageDiv.text(message);
    }

    function updateOrderSummary() {
        if (appliedCoupon) {
            const discount = appliedCoupon.discount;
            const total = {{ $total }};
            const newTotal = total - discount;

            $('#discount-row').show();
            $('#discount-amount').text('-₹' + discount.toFixed(2));
            $('#total').text('₹' + newTotal.toFixed(2));
        } else {
            $('#discount-row').hide();
            $('#total').text('₹{{ number_format($total, 2) }}');
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
                console.log('Order submission response:', response);
                if (response.success && response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    showToast('Order placed successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = response.redirect || '{{ route("home") }}';
                    }, 2000);
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
