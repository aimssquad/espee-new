@extends('layouts.user-panel')

@section('title', 'Profile - My Account')

@section('breadcrumb')
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-edit me-2"></i>Profile</h2>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('user-panel.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y') }}" readonly>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                        <a href="{{ route('user-panel.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Account Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                            <h5>{{ \App\Models\Order::where('email', $user->email)->count() }}</h5>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-rupee-sign fa-2x text-success mb-2"></i>
                            <h5>₹{{ number_format(\App\Models\Order::where('email', $user->email)->sum('total_amount'), 0) }}</h5>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                            <h5>{{ \App\Models\UserAddress::where('user_id', $user->id)->count() }}</h5>
                            <small class="text-muted">Saved Addresses</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                            <h5>{{ $user->created_at->diffInDays(now()) }}</h5>
                            <small class="text-muted">Days as Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
