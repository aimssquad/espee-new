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
                        <div class="card product-card h-100">
                            <img src="{{ $product->main_image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p class="card-text text-muted small">{{ $product->model_no }}</p>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
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
