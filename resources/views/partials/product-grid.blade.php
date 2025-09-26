<div class="row g-4">
    @forelse($products as $product)
    <div class="col-lg-4 col-md-6">
        <div class="product-card h-100">
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                <img src="{{ $product->main_image }}"
                     class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-muted mb-2">{{ $product->model_no }}</p>
                    <p class="price mb-3">{{ $product->price_range }}</p>
                    @if($product->variants->count() > 1)
                    <div class="color-options">
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
                </div>
            </a>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>No products found</h4>
            <p class="text-muted">Try adjusting your filters or search criteria</p>
            <a href="{{ route('products.index') }}" class="btn btn-dark">View All Products</a>
        </div>
    </div>
    @endforelse
</div>

@if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
<div class="mt-4">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
