@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="product-images">
                <div class="main-image mb-3">
                    <img id="main-product-image" 
                         src="{{ $product->defaultVariant() ? $product->defaultVariant()->image_url : 'https://via.placeholder.com/600x400/000000/FFFFFF?text=' . urlencode($product->name) }}" 
                         class="img-fluid" alt="{{ $product->name }}">
                </div>
                <div class="variant-thumbnails d-flex gap-2 flex-wrap">
                    @foreach($product->variants as $variant)
                    <div class="thumbnail-item" data-variant-id="{{ $variant->id }}">
                        <img src="{{ $variant->image_url }}" 
                             class="img-thumbnail variant-thumbnail" 
                             alt="{{ $product->name }} - {{ $variant->color->name }}"
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <h1 class="h2 mb-2">{{ $product->name }}</h1>
            <p class="text-muted mb-4">Model: {{ $product->model_no }}</p>
            
            <div class="price-section mb-4">
                <h3 id="product-price" class="mb-0">{{ $product->price_range }}</h3>
            </div>

            @if($product->description)
            <div class="description-section mb-4">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>
            @endif

            <form id="add-to-cart-form">
                @csrf
                <!-- Color Selection -->
                <div class="mb-4">
                    <h5 class="mb-3">Select Color</h5>
                    <div class="color-selection d-flex gap-2 flex-wrap">
                        @foreach($product->variants as $variant)
                        <label class="color-option">
                            <input type="radio" name="variant_id" value="{{ $variant->id }}" 
                                   class="variant-radio" 
                                   data-price="{{ $variant->price }}"
                                   data-stock="{{ $variant->stock }}"
                                   data-image="{{ $variant->image_url }}"
                                   data-color="{{ $variant->color->name }}"
                                   {{ $loop->first ? 'checked' : '' }}>
                            <span class="color-swatch-large" 
                                  style="background-color: {{ $variant->color->hex_code }}"
                                  title="{{ $variant->color->name }}"></span>
                            <span class="color-name">{{ $variant->color->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-4">
                    <p id="stock-status" class="mb-0">
                        <span class="badge bg-success">In Stock</span>
                        <small class="text-muted ms-2">(<span id="stock-count">0</span> available)</small>
                    </p>
                </div>

                <!-- Quantity -->
                <div class="mb-4">
                    <h5 class="mb-3">Quantity</h5>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-dark" id="qty-decrease">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" 
                               class="form-control mx-2 text-center" style="width: 80px;">
                        <button type="button" class="btn btn-outline-dark" id="qty-increase">+</button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-dark btn-lg w-100" id="add-to-cart-btn">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                </div>
            </form>

            <!-- Product Features -->
            <div class="product-features">
                <h5 class="mb-3">Features</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check me-2"></i>100% UV Protection</li>
                    <li><i class="fas fa-check me-2"></i>Premium Quality Materials</li>
                    <li><i class="fas fa-check me-2"></i>1 Year Warranty</li>
                    <li><i class="fas fa-check me-2"></i>Free Shipping</li>
                </ul>
            </div>

            <!-- Product Details Table -->
            <div class="product-details-table mt-4">
                <h5 class="mb-3">Specifications</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Category</td>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    @if($product->subcategory)
                    <tr>
                        <td class="fw-bold">Subcategory</td>
                        <td>{{ $product->subcategory->name }}</td>
                    </tr>
                    @endif
                    @if($product->shape)
                    <tr>
                        <td class="fw-bold">Shape</td>
                        <td>{{ $product->shape->name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold">Available Colors</td>
                        <td>{{ $product->variants->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products mt-5">
        <h3 class="mb-4">You May Also Like</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card h-100">
                    <a href="{{ route('products.show', $relatedProduct) }}" class="text-decoration-none text-dark">
                        <img src="{{ $relatedProduct->defaultVariant() ? $relatedProduct->defaultVariant()->image_url : 'https://via.placeholder.com/600x400/000000/FFFFFF?text=' . urlencode($relatedProduct->name) }}" 
                             class="card-img-top" alt="{{ $relatedProduct->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="text-muted mb-2">{{ $relatedProduct->model_no }}</p>
                            <p class="price mb-0">{{ $relatedProduct->price_range }}</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.product-images .main-image {
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}

.variant-thumbnail {
    transition: opacity 0.3s ease;
}

.variant-thumbnail:hover {
    opacity: 0.8;
}

.color-option {
    position: relative;
    cursor: pointer;
}

.color-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.color-swatch-large {
    display: inline-block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid #e0e0e0;
    transition: all 0.3s ease;
}

.color-option input[type="radio"]:checked + .color-swatch-large {
    transform: scale(1.1);
    border-color: #000;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
}

.color-name {
    display: block;
    text-align: center;
    font-size: 12px;
    margin-top: 5px;
}

.product-features li {
    padding: 0.25rem 0;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let maxStock = {{ $product->defaultVariant() ? $product->defaultVariant()->stock : 0 }};
    
    // Update product details when variant is selected
    $('.variant-radio').on('change', function() {
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        const image = $(this).data('image');
        const color = $(this).data('color');
        
        // Update price
        $('#product-price').text('$' + parseFloat(price).toFixed(2));
        
        // Update main image
        $('#main-product-image').attr('src', image);
        
        // Update stock status
        maxStock = stock;
        $('#stock-count').text(stock);
        
        if (stock > 0) {
            $('#stock-status').html('<span class="badge bg-success">In Stock</span> <small class="text-muted ms-2">(' + stock + ' available)</small>');
            $('#add-to-cart-btn').prop('disabled', false);
            $('#quantity').attr('max', stock).val(1);
        } else {
            $('#stock-status').html('<span class="badge bg-danger">Out of Stock</span>');
            $('#add-to-cart-btn').prop('disabled', true);
        }
    });
    
    // Quantity controls
    $('#qty-decrease').on('click', function() {
        const currentQty = parseInt($('#quantity').val());
        if (currentQty > 1) {
            $('#quantity').val(currentQty - 1);
        }
    });
    
    $('#qty-increase').on('click', function() {
        const currentQty = parseInt($('#quantity').val());
        if (currentQty < maxStock) {
            $('#quantity').val(currentQty + 1);
        }
    });
    
    $('#quantity').on('change', function() {
        const qty = parseInt($(this).val());
        if (qty < 1) {
            $(this).val(1);
        } else if (qty > maxStock) {
            $(this).val(maxStock);
        }
    });
    
    // Thumbnail click
    $('.variant-thumbnail').on('click', function() {
        const variantId = $(this).closest('.thumbnail-item').data('variant-id');
        $('input[name="variant_id"][value="' + variantId + '"]').prop('checked', true).trigger('change');
    });
    
    // Add to cart
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        const variantId = $('input[name="variant_id"]:checked').val();
        const quantity = $('#quantity').val();
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                variant_id: variantId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    updateCartCount(response.cart_count);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'An error occurred', 'error');
            }
        });
    });
    
    // Trigger initial variant selection
    $('.variant-radio:checked').trigger('change');
});
</script>
@endpush