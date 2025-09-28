@extends('layouts.app')

@section('title', 'Espee - Premium Sunglasses & Frames')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Premium Sunglasses & Frames</h1>
                <p class="lead mb-4">Discover our exclusive collection of designer sunglasses and optical frames. Crafted with precision and style for the modern lifestyle.</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&h=400&fit=crop" alt="Premium Sunglasses" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Categories</h2>
        <div class="row">
            @foreach($featuredCategories as $category)
            <div class="col-md-6 mb-4">
                <div class="card product-card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->products->count() }} products available</p>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary">View Collection</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    @if($product->defaultVariant() && $product->defaultVariant()->image)
                        <img src="{{ $product->defaultVariant()->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/300x250/000000/FFFFFF?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small">{{ $product->model_no }}</p>
                        <div class="price mb-3">
                            {{ $product->price_range }}
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                </div>
                <h5>Free Shipping</h5>
                <p class="text-muted">Free shipping on orders over $100</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <h5>Quality Guarantee</h5>
                <p class="text-muted">Premium materials and craftsmanship</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-headset fa-3x text-primary"></i>
                </div>
                <h5>24/7 Support</h5>
                <p class="text-muted">Customer support whenever you need it</p>
            </div>
        </div>
    </div>
</section>
@endsection
