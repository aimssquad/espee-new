@extends('layouts.user-panel')

@section('title', 'Address Book - My Account')

@section('breadcrumb')
<li class="breadcrumb-item active">Address Book</li>
@endsection

@section('account-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-map-marker-alt me-2"></i>Address Book</h2>
    <a href="{{ route('user-panel.addresses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Address
    </a>
</div>

@if($addresses->count() > 0)
    <div class="row">
        @foreach($addresses as $address)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card address-card {{ $address->is_default ? 'default' : '' }}">
                <div class="card-body">
                    @if($address->is_default)
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success">
                                <i class="fas fa-star me-1"></i>Default
                            </span>
                        </div>
                    @endif

                    <h6 class="card-title">{{ $address->name }}</h6>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                        @if($address->email)
                            <br><i class="fas fa-envelope me-1"></i>{{ $address->email }}
                        @endif
                    </p>
                    <p class="card-text">{{ $address->address }}</p>
                    <p class="card-text text-muted small">
                        {{ $address->city }}, {{ $address->state }} {{ $address->pincode }}
                    </p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('user-panel.addresses.edit', $address) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        @if(!$address->is_default)
                            <form method="POST" action="{{ route('user-panel.addresses.set-default', $address) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-star"></i> Set Default
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('user-panel.addresses.delete', $address) }}"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this address?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-map-marker-alt fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">No addresses found</h4>
            <p class="text-muted mb-4">Add your first address to make checkout faster!</p>
            <a href="{{ route('user-panel.addresses.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Add Your First Address
            </a>
        </div>
    </div>
@endif
@endsection
