@foreach($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card product-card h-100 position-relative">
            <!-- Product Image with Overlay Actions -->
            <div class="product-image-container position-relative">
                <img src="{{ $product->main_image }}"
                     class="card-img-top"
                     alt="{{ $product->name }}"
                     style="height: 250px; object-fit: cover;">

            </div>

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted small">{{ $product->category->name }}</p>

                <div class="mt-auto">
                    <div class="price mb-2">
                        <span class="h5 text-success">{{ $product->price_range }}</span>
                        @if($product->variants->where('stock', '>', 0)->count() > 0)
                            <small class="text-success d-block">In Stock</small>
                        @else
                            <small class="text-danger d-block">Out of Stock</small>
                        @endif
                    </div>

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
