@extends('layouts.user-panel')

@section('title', (isset($address) ? 'Edit Address' : 'Add New Address') . ' - My Account')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('user-panel.addresses') }}">Address Book</a>
</li>
<li class="breadcrumb-item active">{{ isset($address) ? 'Edit Address' : 'Add New Address' }}</li>
@endsection

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-map-marker-alt me-2"></i>
        {{ isset($address) ? 'Edit Address' : 'Add New Address' }}
    </h2>
    <a href="{{ route('user-panel.addresses') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Addresses
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ isset($address) ? route('user-panel.addresses.update', $address) : route('user-panel.addresses.store') }}">
                    @csrf
                    @if(isset($address))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $address->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $address->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $address->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address *</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3" required>{{ old('address', $address->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pincode" class="form-label">Pincode *</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                                       id="pincode" name="pincode" maxlength="6" pattern="[0-9]{6}"
                                       value="{{ old('pincode', $address->pincode ?? '') }}" required>
                                <button class="btn btn-outline-secondary" type="button" id="lookup-pincode">
                                    <i class="fas fa-search" id="lookup-icon"></i>
                                </button>
                            </div>
                            <div id="pincode-lookup-message" class="form-text"></div>
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" value="{{ old('city', $address->city ?? '') }}" readonly required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror"
                                   id="state" name="state" value="{{ old('state', $address->state ?? '') }}" readonly required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                   value="1" {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($address) ? 'Update Address' : 'Add Address' }}
                        </button>
                        <a href="{{ route('user-panel.addresses') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Pincode lookup functionality (same as checkout)
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

        // Use Postal PIN Code API
        $.ajax({
            url: `https://api.postalpincode.in/pincode/${pincode}`,
            method: 'GET',
            timeout: 10000,
            success: function(response) {
                if (response && response[0] && response[0].Status === 'Success' && response[0].PostOffice) {
                    const postOffice = response[0].PostOffice[0];
                    updateLocationFields(postOffice.District, postOffice.State);
                    showPincodeMessage(`Location found: ${postOffice.District}, ${postOffice.State}`, 'success');
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
});
</script>
@endpush
