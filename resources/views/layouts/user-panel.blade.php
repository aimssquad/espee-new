@extends('layouts.app')

@section('title', 'My Account - Espee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="account-sidebar">
                <div class="p-3">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-user-circle me-2"></i>My Account
                    </h6>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('user-panel.dashboard') ? 'active' : '' }}"
                           href="{{ route('user-panel.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('user-panel.orders*') ? 'active' : '' }}"
                           href="{{ route('user-panel.orders') }}">
                            <i class="fas fa-shopping-bag me-2"></i>My Orders
                        </a>
                        <a class="nav-link {{ request()->routeIs('user-panel.addresses*') ? 'active' : '' }}"
                           href="{{ route('user-panel.addresses') }}">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Book
                        </a>
                        <a class="nav-link {{ request()->routeIs('user-panel.profile') ? 'active' : '' }}"
                           href="{{ route('user-panel.profile') }}">
                            <i class="fas fa-user-edit me-2"></i>Profile
                        </a>

                        <hr class="my-3">

                        <h6 class="text-muted mb-2">
                            <i class="fas fa-store me-2"></i>Store
                        </h6>
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-glasses me-2"></i>Browse Products
                        </a>
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                            <span class="badge bg-primary ms-2" id="sidebar-cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="account-content p-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('user-panel.dashboard') }}">My Account</a>
                        </li>
                        @yield('breadcrumb')
                    </ol>
                </nav>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('account-content')
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.account-sidebar {
    background-color: #f8f9fa;
    border-right: 1px solid #e9ecef;
    min-height: calc(100vh - 76px);
}

.account-sidebar .nav-link {
    color: #6c757d;
    padding: 12px 16px;
    border-radius: 8px;
    margin: 4px 0;
    transition: all 0.3s ease;
    border: none;
    background: none;
}

.account-sidebar .nav-link:hover {
    color: var(--primary-color);
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(5px);
}

.account-sidebar .nav-link.active {
    color: var(--primary-color);
    background-color: rgba(0, 123, 255, 0.15);
    font-weight: 600;
}

.account-sidebar .nav-link i {
    width: 20px;
    margin-right: 10px;
}

.account-content {
    background-color: #fff;
    min-height: calc(100vh - 76px);
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.stats-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    color: white;
    border-radius: 12px;
}

.stats-card .card-body {
    padding: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.order-status {
    font-size: 0.875rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

.status-pending { background-color: #fff3cd; color: #856404; }
.status-processing { background-color: #d1ecf1; color: #0c5460; }
.status-shipped { background-color: #cce5ff; color: #004085; }
.status-delivered { background-color: #d4edda; color: #155724; }
.status-cancelled { background-color: #f8d7da; color: #721c24; }

.address-card {
    border-left: 4px solid var(--primary-color);
    transition: all 0.3s ease;
}

.address-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.address-card.default {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.tracking-step {
    color: #6c757d;
    transition: all 0.3s ease;
}

.tracking-step.active {
    color: var(--primary-color);
}

.tracking-step i {
    transition: all 0.3s ease;
}

.tracking-step.active i {
    color: var(--primary-color);
    transform: scale(1.1);
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

@media (max-width: 768px) {
    .account-sidebar {
        min-height: auto;
        border-right: none;
        border-bottom: 1px solid #e9ecef;
    }

    .account-content {
        min-height: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Update cart count in sidebar when cart changes
    function updateSidebarCartCount() {
        $.get('{{ route("cart.count") }}', function(data) {
            $('#sidebar-cart-count').text(data.count);
        });
    }

    // Update cart count on page load
    updateSidebarCartCount();

    // Listen for cart updates (if any cart update events are fired)
    $(document).on('cartUpdated', function() {
        updateSidebarCartCount();
    });
});
</script>
@endpush
