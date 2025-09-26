@foreach($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card product-card h-100">
            <img src="{{ $product->main_image }}"
                 class="card-img-top"
                 alt="{{ $product->name }}"
                 style="height: 250px; object-fit: cover;">

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted small">{{ $product->category->name }}</p>

                <div class="mt-auto">
                    <div class="price mb-2">
                        <span class="h5 text-success">{{ $product->price_range }}</span>
                    </div>

                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
