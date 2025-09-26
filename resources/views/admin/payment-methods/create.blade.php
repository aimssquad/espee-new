@extends('layouts.admin')

@section('title', 'Create Payment Method')
@section('page-title', 'Create Payment Method')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">Create Payment Method</h2>
        <p class="text-muted mb-0">Add a new payment method to your system</p>
    </div>
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Payment Methods
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Payment Method Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payment-methods.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Internal name (e.g., Razorpay, PayU, COD)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name *</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                       id="display_name" name="display_name"
                                       value="{{ old('display_name') }}" required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Name shown to customers</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon Class</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                       id="icon" name="icon"
                                       value="{{ old('icon') }}"
                                       placeholder="e.g., fas fa-credit-card">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">FontAwesome icon class (e.g., fas fa-credit-card)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order"
                                       value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lower numbers appear first</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Description shown to customers</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Only active payment methods are shown to customers</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Payment Method
                        </button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Available Payment Types</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Razorpay</h6>
                    <p class="small text-muted mb-2">Online payment gateway supporting UPI, Cards, Net Banking, Wallets</p>
                    <code>razorpay</code>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary">PayU</h6>
                    <p class="small text-muted mb-2">Payment gateway with multiple payment options</p>
                    <code>payu</code>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary">Cash on Delivery</h6>
                    <p class="small text-muted mb-2">Pay when your order is delivered</p>
                    <code>cod</code>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary">Custom</h6>
                    <p class="small text-muted mb-2">Any other payment method you want to add</p>
                    <code>custom-name</code>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Icon Examples</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <i class="fas fa-credit-card fa-2x text-primary"></i>
                        <br><small>fas fa-credit-card</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        <br><small>fas fa-money-bill-wave</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fab fa-cc-razorpay fa-2x text-info"></i>
                        <br><small>fab fa-cc-razorpay</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fas fa-university fa-2x text-warning"></i>
                        <br><small>fas fa-university</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
