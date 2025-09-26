@extends('layouts.app')

@section('title', $product->name . ' - Espee')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <!-- Product Images -->
            <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="main-carousel">
                    @php $imageIndex = 0; @endphp
                    @foreach($product->variants as $variant)
                        @if($variant->images->count() > 0)
                            @foreach($variant->images as $image)
                                <div class="carousel-item {{ $imageIndex === 0 ? 'active' : '' }}" data-variant-id="{{ $variant->id }}">
                                    <img src="{{ $image->image_url }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                                </div>
                                @php $imageIndex++; @endphp
                            @endforeach
                        @else
                            <div class="carousel-item {{ $imageIndex === 0 ? 'active' : '' }}" data-variant-id="{{ $variant->id }}">
                                <img src="{{ $variant->image_url }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                            </div>
                            @php $imageIndex++; @endphp
                        @endif
                    @endforeach
                </div>
                @if($imageIndex > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#product-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#product-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>

            <!-- Image Thumbnails -->
            <div class="mt-3">
                <div class="d-flex flex-wrap gap-2" id="image-thumbnails">
                    @php $thumbIndex = 0; @endphp
                    @foreach($product->variants as $variant)
                        @if($variant->images->count() > 0)
                            @foreach($variant->images as $image)
                                <img src="{{ $image->image_url }}"
                                     class="img-thumbnail {{ $thumbIndex === 0 ? 'active' : '' }}"
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                     data-bs-target="#product-carousel"
                                     data-bs-slide-to="{{ $thumbIndex }}"
                                     alt="{{ $product->name }}">
                                @php $thumbIndex++; @endphp
                            @endforeach
                        @else
                            <img src="{{ $variant->image_url }}"
                                 class="img-thumbnail {{ $thumbIndex === 0 ? 'active' : '' }}"
                                 style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                 data-bs-target="#product-carousel"
                                 data-bs-slide-to="{{ $thumbIndex }}"
                                 alt="{{ $product->name }}">
                            @php $thumbIndex++; @endphp
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Color Variants -->
            @if($product->variants->count() > 1)
            <div class="mt-3">
                <h6>Available Colors:</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->variants as $variant)
                    <button class="btn btn-outline-secondary color-variant"
                            data-variant-id="{{ $variant->id }}"
                            data-price="{{ $variant->price }}"
                            data-images="{{ $variant->images->pluck('image_url')->toJson() }}"
                            data-image="{{ $variant->image_url }}"
                            style="width: 40px; height: 40px; background-color: {{ $variant->color->hex_code }}; border-radius: 50%;">
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->model_no }}</p>

            <div class="price mb-3">
                <span id="current-price" class="h4">₹{{ number_format($product->variants->first()->price ?? $product->base_price, 2) }}</span>
            </div>

            <div class="mb-4">
                <h6>Description</h6>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mb-4">
                <h6>Specifications</h6>
                <ul class="list-unstyled">
                    <li><strong>Category:</strong> {{ $product->category->name }}</li>
                    @if($product->subcategory)
                        <li><strong>Subcategory:</strong> {{ $product->subcategory->name }}</li>
                    @endif
                    @if($product->shape)
                        <li><strong>Shape:</strong> {{ $product->shape->name }}</li>
                    @endif
                </ul>
            </div>

            <!-- Add to Cart Form -->
            <form id="add-to-cart-form">
                @csrf
                <input type="hidden" name="variant_id" id="selected-variant" value="{{ $product->variants->first()->id ?? '' }}">

                <div class="row mb-3">
                    <div class="col-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->variants->first()->stock ?? 0 }}">
                    </div>
                    <div class="col-8">
                        <label class="form-label">Stock</label>
                        <div id="stock-info" class="form-control-plaintext">
                            {{ $product->variants->first()->stock ?? 0 }} available
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100" id="add-to-cart-btn">
                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                </button>
            </form>

            <!-- Product Features -->
            <div class="mt-5">
                <div class="row text-center">
                    <div class="col-4">
                        <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                        <p class="small">Free Shipping</p>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-undo fa-2x text-primary mb-2"></i>
                        <p class="small">Easy Returns</p>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                        <p class="small">Warranty</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h4>Related Products</h4>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    @if($relatedProduct->defaultVariant() && $relatedProduct->defaultVariant()->image)
                        <img src="{{ $relatedProduct->defaultVariant()->image_url }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/300x200/000000/FFFFFF?text={{ urlencode($relatedProduct->name) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <div class="price mb-3">
                            {{ $relatedProduct->price_range }}
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Color variant selection
    $('.color-variant').on('click', function() {
        $('.color-variant').removeClass('btn-primary').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary');

        const variantId = $(this).data('variant-id');
        const price = $(this).data('price');
        const images = $(this).data('images');
        const image = $(this).data('image');

        $('#selected-variant').val(variantId);
        $('#current-price').text('₹' + parseFloat(price).toFixed(2));

        // Update carousel with multiple images
        if (images && images.length > 0) {
            updateCarouselWithImages(images);
        } else {
            // Fallback to single image
            $('.carousel-item.active img').attr('src', image);
        }

        // Update stock info
        updateStockInfo(variantId);
    });

    function updateCarouselWithImages(images) {
        const carouselInner = $('#main-carousel');
        const thumbnails = $('#image-thumbnails');

        // Clear existing carousel items
        carouselInner.empty();
        thumbnails.empty();

        // Add new carousel items
        images.forEach((imageUrl, index) => {
            const isActive = index === 0 ? 'active' : '';
            const carouselItem = `
                <div class="carousel-item ${isActive}">
                    <img src="${imageUrl}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                </div>
            `;
            carouselInner.append(carouselItem);

            // Add thumbnail
            const thumbnail = `
                <img src="${imageUrl}"
                     class="img-thumbnail ${isActive}"
                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                     data-bs-target="#product-carousel"
                     data-bs-slide-to="${index}"
                     alt="{{ $product->name }}">
            `;
            thumbnails.append(thumbnail);
        });

        // Reinitialize carousel
        $('#product-carousel').carousel('dispose').carousel();
    }

    // Add to cart
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showToast('success', response.message);

                    // Update cart count
                    $('#cart-count').text(response.cart_count);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast('error', response.message || 'Error adding to cart');
            }
        });
    });

    function updateStockInfo(variantId) {
        // This would typically make an AJAX call to get stock info
        // For now, we'll just show a placeholder
        $('#stock-info').text('Stock information loading...');
    }

    // showToast function is now available globally from app layout
});
</script>
@endpush
