@extends('layouts.app')

@section('title', $product->name . ' - Espee')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <!-- Product Images -->
            @php
                $initialVariant = method_exists($product, 'defaultVariant') && $product->defaultVariant()
                    ? $product->defaultVariant()
                    : $product->variants->first();
            @endphp
            <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="main-carousel">
                    @php $imageIndex = 0; @endphp
                    @if($initialVariant)
                        @if($initialVariant->images->count() > 0)
                            @foreach($initialVariant->images as $image)
                                <div class="carousel-item {{ $imageIndex === 0 ? 'active' : '' }}" data-variant-id="{{ $initialVariant->id }}">
                                    <img src="{{ $image->image_url ?? 'https://via.placeholder.com/1000x700/000000/FFFFFF?text=Product+Image' }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                                </div>
                                @php $imageIndex++; @endphp
                            @endforeach
                        @else
                            <div class="carousel-item active" data-variant-id="{{ $initialVariant->id }}">
                                <img src="{{ $initialVariant->image_url ?? 'https://via.placeholder.com/1000x700/000000/FFFFFF?text=Product+Image' }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                            </div>
                            @php $imageIndex++; @endphp
                        @endif
                    @endif
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
                    @if($initialVariant)
                        @if($initialVariant->images->count() > 0)
                            @foreach($initialVariant->images as $image)
                                <img src="{{ $image->image_url ?? 'https://via.placeholder.com/200x200/000000/FFFFFF?text=Thumb' }}"
                                     class="img-thumbnail {{ $thumbIndex === 0 ? 'active' : '' }}"
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                     data-bs-target="#product-carousel"
                                     data-bs-slide-to="{{ $thumbIndex }}"
                                     alt="{{ $product->name }}">
                                @php $thumbIndex++; @endphp
                            @endforeach
                        @else
                            <img src="{{ $initialVariant->image_url ?? 'https://via.placeholder.com/200x200/000000/FFFFFF?text=Thumb' }}"
                                 class="img-thumbnail active"
                                 style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                 data-bs-target="#product-carousel"
                                 data-bs-slide-to="0"
                                 alt="{{ $product->name }}">
                            @php $thumbIndex++; @endphp
                        @endif
                    @endif
                </div>
            </div>

            <!-- Color Variants -->
            @if($product->variants->count() > 1)
            <div class="mt-3">
                <h6>Available Colors:</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->variants as $variant)
                    <button class="btn {{ ($initialVariant && $variant->id === $initialVariant->id) ? 'btn-primary' : 'btn-outline-secondary' }} color-variant"
                            data-variant-id="{{ $variant->id }}"
                            data-price="{{ $variant->price }}"
                            data-stock="{{ $variant->stock ?? 0 }}"
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
                <span id="current-price" class="h4">₹{{ number_format(($initialVariant->price ?? $product->base_price), 2) }}</span>
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
                <input type="hidden" name="variant_id" id="selected-variant" value="{{ $initialVariant->id ?? '' }}">

                <div class="row mb-3">
                    <div class="col-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $initialVariant->stock ?? 0 }}">
                    </div>
                    <div class="col-8">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-label mb-1">Stock</label>
                                <div id="stock-info" class="form-control-plaintext p-0">
                                    {{ $initialVariant->stock ?? 0 }} available
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                    <i class="fas fa-ruler-combined me-1"></i> Size Chart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill" id="add-to-cart-btn">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                    <button type="button" class="btn btn-dark btn-lg flex-fill" id="buy-now-btn">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </button>
                </div>

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

<!-- Product Highlights (Zigzag Sections) -->
@if(isset($highlights) && $highlights->count())
<div class="container py-5">
    <div class="product-highlights">
        @foreach($highlights as $i => $section)
        <div class="row align-items-center g-4 mb-5 {{ $i % 2 === 1 ? 'flex-row-reverse' : '' }}">
            <div class="col-lg-6">
                <img src="{{ $section->image_url }}" class="img-fluid rounded-3 shadow-sm w-100" alt="{{ $section->title }}" style="object-fit: cover; height: 420px;">
            </div>
            <div class="col-lg-6">
                @if($section->title)
                <h3 class="fw-bold mb-3">{{ $section->title }}</h3>
                @endif
                @if($section->text)
                <p class="lead text-muted">{{ $section->text }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
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
            const fallback = image || 'https://via.placeholder.com/1000x700/000000/FFFFFF?text=Product+Image';
            $('.carousel-item.active img').attr('src', fallback);
        }

        // Update stock info and quantity max based on selected variant
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
                    <img src="${imageUrl || 'https://via.placeholder.com/1000x700/000000/FFFFFF?text=Product+Image'}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                </div>
            `;
            carouselInner.append(carouselItem);

            // Add thumbnail
            const thumbnail = `
                <img src="${imageUrl || 'https://via.placeholder.com/200x200/000000/FFFFFF?text=Thumb'}"
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
        const variantButton = $(`.color-variant[data-variant-id='${variantId}']`);
        const stock = parseInt(variantButton.data('stock')) || 0;
        $('#stock-info').text(`${stock} available`);
        $('#quantity').attr('max', stock);
    }

    // showToast function is now available globally from app layout

    // Buy Now: add to cart then go to checkout
    $('#buy-now-btn').on('click', function() {
        const form = $('#add-to-cart-form');
        const formData = form.serialize();

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = '{{ route("checkout.index") }}';
                } else {
                    showToast('error', response.message || 'Unable to proceed to checkout');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                showToast('error', response.message || 'Unable to proceed to checkout');
            }
        });
    });
});
</script>
@endpush

@push('modals')
<!-- Size Chart Modal -->
<div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizeChartLabel">Size Chart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img src="https://via.placeholder.com/1000x700?text=Size+Chart" alt="Size Chart" class="img-fluid w-100" style="display:block;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </div>
@endpush
