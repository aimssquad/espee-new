@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>ELEVATE YOUR STYLE</h1>
            <p>Premium sunglasses and frames for the modern lifestyle</p>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">SHOP NOW</a>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">FEATURED COLLECTIONS</h2>
            <div class="row g-4">
                @foreach($featuredCategories as $category)
                <div class="col-md-6">
                    <div class="position-relative overflow-hidden" style="height: 400px;">
                        <img src="https://via.placeholder.com/800x600/000000/FFFFFF?text={{ urlencode($category->name) }}"
                             class="w-100 h-100 object-fit-cover" alt="{{ $category->name }}">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4 text-white"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <h3 class="mb-3">{{ strtoupper($category->name) }}</h3>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                               class="btn btn-outline-light">EXPLORE COLLECTION</a>
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
            <h2 class="text-center mb-5">FEATURED PRODUCTS</h2>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card h-100 position-relative">
                        <!-- Product Image with Overlay Actions -->
                        <div class="product-image-container position-relative">
                            <img src="{{ $product->main_image }}"
                                 class="card-img-top" alt="{{ $product->name }}">

                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted mb-2">{{ $product->model_no }}</p>
                            <p class="price mb-3">{{ $product->price_range }}</p>

                            @if($product->variants->count() > 1)
                            <div class="color-options mb-3">
                                @foreach($product->variants->take(5) as $variant)
                                <span class="color-swatch"
                                      style="background-color: {{ $variant->color->hex_code }}"
                                      title="{{ $variant->color->name }}"></span>
                                @endforeach
                                @if($product->variants->count() > 5)
                                <small class="text-muted">+{{ $product->variants->count() - 5 }} more</small>
                                @endif
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-success btn-sm w-100 add-to-cart-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-variant-id="{{ $product->variants->where('stock', '>', 0)->first()?->id }}"
                                            @if($product->variants->where('stock', '>', 0)->count() == 0) disabled @endif>
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-danger btn-sm w-100 wishlist-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            title="Add to Wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('products.index') }}" class="btn btn-dark btn-lg">VIEW ALL PRODUCTS</a>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    @include('partials.video-section', ['videoSetting' => $videoSetting ?? null])

    <!-- Why Choose Us -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">WHY CHOOSE ESPEE</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <i class="fas fa-glasses fa-3x mb-3"></i>
                    <h4>Premium Quality</h4>
                    <p>Crafted with the finest materials and attention to detail</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                    <h4>UV Protection</h4>
                    <p>100% UV protection to keep your eyes safe and healthy</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-truck fa-3x mb-3"></i>
                    <h4>Fast Shipping</h4>
                    <p>Free shipping on all orders with quick delivery</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add to Cart functionality for home page
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const productId = button.data('product-id');
        const productName = button.data('product-name');
        const variantId = button.data('variant-id');

        // Check if variant ID exists
        if (!variantId) {
            showToast('error', 'Please select a variant first');
            return;
        }

        // Add loading state
        button.addClass('loading');
        button.prop('disabled', true);

        // AJAX request to add to cart
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                variant_id: variantId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Show success message
                showToast('success', `${productName} added to cart!`);

                // Update cart count
                updateCartCount();

                // Reset button state
                button.removeClass('loading');
                button.prop('disabled', false);

                // Show success animation
                button.html('<i class="fas fa-check"></i>');
                setTimeout(() => {
                    button.html('<i class="fas fa-shopping-cart"></i>');
                }, 2000);
            },
            error: function(xhr) {
                // Show error message
                const errorMessage = xhr.responseJSON?.message || 'Failed to add item to cart';
                showToast('error', errorMessage);

                // Reset button state
                button.removeClass('loading');
                button.prop('disabled', false);
            }
        });
    });

    // Wishlist functionality for home page
    $('.wishlist-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const productId = button.data('product-id');
        const productName = button.data('product-name');

        // Toggle wishlist state
        button.toggleClass('active');

        if (button.hasClass('active')) {
            // Add to wishlist
            button.removeClass('btn-outline-danger').addClass('btn-danger');
            button.html('<i class="fas fa-heart"></i>');
            showToast('success', `${productName} added to wishlist!`);
        } else {
            // Remove from wishlist
            button.removeClass('btn-danger').addClass('btn-outline-danger');
            button.html('<i class="far fa-heart"></i>');
            showToast('info', `${productName} removed from wishlist!`);
        }

        // Here you can add AJAX call to save wishlist to database
        // For now, it's just visual feedback
    });

    // Update cart count function
    function updateCartCount() {
        $.ajax({
            url: '{{ route("cart.count") }}',
            method: 'GET',
            success: function(response) {
                $('#cart-count').text(response.count);
            }
        });
    }
});
</script>
@endpush
