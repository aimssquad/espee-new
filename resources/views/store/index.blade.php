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

<!-- Gender Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Shop by Gender</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card gender-card-simple h-100 text-center">
                    <div class="card-img-top" style="height: 280px; overflow: hidden; position: relative;">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=280&fit=crop&crop=face"
                             alt="Men's Collection"
                             class="img-fluid"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="image-overlay-simple">
                            <i class="fas fa-male fa-3x text-white"></i>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3">Men's Collection</h4>
                        <p class="card-text mb-4">Discover our exclusive range of sunglasses and frames designed for men.</p>
                        <div class="mt-auto">
                            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="btn btn-dark btn-lg w-100">
                                <i class="fas fa-male me-2"></i>Shop Men's Collection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card gender-card-simple h-100 text-center">
                    <div class="card-img-top" style="height: 280px; overflow: hidden; position: relative;">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=400&h=280&fit=crop&crop=face"
                             alt="Women's Collection"
                             class="img-fluid"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="image-overlay-simple">
                            <i class="fas fa-female fa-3x text-white"></i>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3">Women's Collection</h4>
                        <p class="card-text mb-4">Explore our elegant and stylish collection crafted for women.</p>
                        <div class="mt-auto">
                            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="btn btn-dark btn-lg w-100">
                                <i class="fas fa-female me-2"></i>Shop Women's Collection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card gender-card-simple h-100 text-center">
                    <div class="card-img-top" style="height: 280px; overflow: hidden; position: relative;">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=280&fit=crop&crop=face"
                             alt="Unisex Collection"
                             class="img-fluid"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="image-overlay-simple">
                            <i class="fas fa-users fa-3x text-white"></i>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3">Unisex Collection</h4>
                        <p class="card-text mb-4">Versatile designs that complement any style and personality.</p>
                        <div class="mt-auto">
                            <a href="{{ route('products.index', ['gender' => 'unisex']) }}" class="btn btn-dark btn-lg w-100">
                                <i class="fas fa-users me-2"></i>Shop Unisex Collection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop by Shape -->
<section class="py-5 shop-by-shape-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Shop by Shape</h2>
            <p class="section-subtitle">Find the perfect frame shape that complements your unique style and personality</p>
            <div class="section-divider"></div>
        </div>

        <div class="row g-4">
            @foreach($shapes as $shape)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="modern-shape-card">
                        <div class="shape-card-header">
                            <div class="shape-icon-wrapper">
                                @if($shape->image)
                                    <img src="{{ asset('storage/' . $shape->image) }}"
                                         alt="{{ $shape->name }}"
                                         class="shape-image">
                                @else
                                    <div class="shape-icon">
                                        @switch(strtolower($shape->name))
                                            @case('round')
                                                <i class="fas fa-circle"></i>
                                                @break
                                            @case('square')
                                                <i class="fas fa-square"></i>
                                                @break
                                            @case('oval')
                                                <i class="fas fa-egg"></i>
                                                @break
                                            @case('cat-eye')
                                                <i class="fas fa-eye"></i>
                                                @break
                                            @case('aviator')
                                                <i class="fas fa-plane"></i>
                                                @break
                                            @case('wayfarer')
                                                <i class="fas fa-glasses"></i>
                                                @break
                                            @case('rectangular')
                                                <i class="fas fa-square-full"></i>
                                                @break
                                            @case('oversized')
                                                <i class="fas fa-expand-arrows-alt"></i>
                                                @break
                                            @default
                                                <i class="fas fa-glasses"></i>
                                        @endswitch
                                    </div>
                                @endif
                            </div>
                            <div class="shape-badge">Popular</div>
                        </div>

                        <div class="shape-card-body">
                            <h4 class="shape-title">{{ $shape->name }}</h4>
                            <p class="shape-description">{{ $shape->description ?: 'Discover our collection of ' . strtolower($shape->name) . ' frames' }}</p>

                            <div class="shape-features">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>UV Protection</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Premium Quality</span>
                                </div>
                            </div>
                        </div>

                        <div class="shape-card-footer">
                            <a href="{{ route('products.shape', $shape->slug) }}" class="modern-shape-btn">
                                <span>Shop {{ $shape->name }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-lg view-all-btn">
                <i class="fas fa-glasses me-2"></i>View All Shapes
            </a>
        </div>
    </div>
</section>

{{-- Video section temporarily hidden --}}

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ $product->main_image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
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
                <p class="text-muted">Free shipping on orders over ₹100</p>
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
