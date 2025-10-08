@extends('layouts.app')

@section('title', 'Products - Espee')

@section('content')
<div class="container py-5">
    @if(request('search'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5 class="mb-2">Search Results for "{{ request('search') }}"</h5>
                    <p class="mb-0">Found {{ $products->count() }} product(s) matching your search.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Filters</h6>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" id="category-filter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Shape Filter -->
                        <div class="mb-3">
                            <label class="form-label">Shape</label>
                            <select name="shape" class="form-select" id="shape-filter">
                                <option value="">All Shapes</option>
                                @foreach($shapes as $shape)
                                    <option value="{{ $shape->id }}" {{ request('shape') == $shape->id ? 'selected' : '' }}>
                                        {{ $shape->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gender Filter -->
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" id="gender-filter">
                                <option value="">All Genders</option>
                                <option value="men" {{ request('gender') == 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ request('gender') == 'women' ? 'selected' : '' }}>Women</option>
                                <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                            </select>
                        </div>

                        <!-- Color Filter -->
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <select name="color" class="form-select" id="color-filter">
                                <option value="">All Colors</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ request('color') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Sort and Results -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Products</h4>
                    <p class="text-muted">{{ $products->total() }} products found</p>
                </div>
                <div>
                    <select class="form-select" id="sort-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="products-container">
                <div class="row" id="products-grid">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card product-card h-100 position-relative">
                            <!-- Product Image with Overlay Actions -->
                            <div class="product-image-container position-relative">
                                <img src="{{ $product->main_image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">

                            </div>

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p class="card-text text-muted small">{{ $product->model_no }}</p>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>

                                <!-- Price and Stock Info -->
                                <div class="price mb-3">
                                    {{ $product->price_range }}
                                    @if($product->variants->where('stock', '>', 0)->count() > 0)
                                        <small class="text-success d-block">In Stock</small>
                                    @else
                                        <small class="text-danger d-block">Out of Stock</small>
                                    @endif
                                </div>

                                <!-- Color Options -->
                                @if($product->variants->count() > 1)
                                <div class="color-options mb-3">
                                    <small class="text-muted d-block mb-2">Available Colors:</small>
                                    @foreach($product->variants->take(4) as $variant)
                                    <span class="color-swatch"
                                          style="background-color: {{ $variant->color->hex_code }}"
                                          title="{{ $variant->color->name }}"></span>
                                    @endforeach
                                    @if($product->variants->count() > 4)
                                    <small class="text-muted">+{{ $product->variants->count() - 4 }} more</small>
                                    @endif
                                </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="mt-auto">
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
                    </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                <div class="pagination-section mt-5">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <!-- Pagination Info -->
                        <div class="pagination-info">
                            <span class="text-muted">
                                <i class="fas fa-list me-1"></i>
                                Showing <strong>{{ $products->firstItem() ?? 0 }}</strong> to <strong>{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> products
                            </span>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="pagination-wrapper">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add to Cart functionality
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

    // Wishlist functionality
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

    // AJAX filtering
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        loadProducts();
    });

    $('#sort-select').on('change', function() {
        loadProducts();
    });

    $('#gender-filter').on('change', function() {
        loadProducts();
    });

    function loadProducts() {
        const formData = $('#filter-form').serialize();
        const sort = $('#sort-select').val();

        $.ajax({
            url: '{{ route("products.index") }}',
            method: 'GET',
            data: formData + '&sort=' + sort,
            success: function(response) {
                $('#products-grid').html(response.products);
                if (response.pagination) {
                    $('.pagination').html(response.pagination);
                }
            },
            error: function() {
                alert('Error loading products. Please try again.');
            }
        });
    }
});
</script>
@endpush
