@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filters-sidebar">
                <h5 class="mb-4">FILTERS</h5>
                
                <form id="filter-form">
                    <!-- Categories -->
                    <div class="filter-group mb-4">
                        <h6>Categories</h6>
                        @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="radio" name="category" 
                                   value="{{ $category->slug }}" id="cat-{{ $category->id }}"
                                   {{ request('category') == $category->slug ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat-{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Shapes -->
                    <div class="filter-group mb-4">
                        <h6>Shapes</h6>
                        @foreach($shapes as $shape)
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="shape[]" 
                                   value="{{ $shape->id }}" id="shape-{{ $shape->id }}"
                                   {{ in_array($shape->id, (array)request('shape', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shape-{{ $shape->id }}">
                                {{ $shape->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Colors -->
                    <div class="filter-group mb-4">
                        <h6>Colors</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colors as $color)
                            <label class="color-filter-label">
                                <input type="checkbox" name="color[]" value="{{ $color->id }}" 
                                       class="color-filter-input"
                                       {{ in_array($color->id, (array)request('color', [])) ? 'checked' : '' }}>
                                <span class="color-swatch" style="background-color: {{ $color->hex_code }}"
                                      title="{{ $color->name }}"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group mb-4">
                        <h6>Price Range</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control" name="min_price" 
                                       placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="max_price" 
                                       placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-dark w-100" id="clear-filters">
                        Clear Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    @if(request('category'))
                        {{ ucfirst(request('category')) }}
                    @else
                        All Products
                    @endif
                </h1>
                
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap">Sort by:</label>
                    <select class="form-select" id="sort-select" style="width: auto;">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div id="products-container">
                @include('partials.product-grid')
            </div>
        </div>
    </div>
</div>

<style>
.filters-sidebar {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.filter-group h6 {
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.color-filter-label {
    position: relative;
    cursor: pointer;
}

.color-filter-input {
    position: absolute;
    opacity: 0;
}

.color-filter-input:checked + .color-swatch {
    transform: scale(1.2);
    border-color: #000;
    box-shadow: 0 0 0 2px #fff, 0 0 0 3px #000;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter form submission
    function applyFilters() {
        const formData = $('#filter-form').serialize();
        const sortValue = $('#sort-select').val();
        const searchParams = new URLSearchParams(formData);
        searchParams.append('sort', sortValue);

        $.ajax({
            url: '{{ route("products.index") }}',
            type: 'GET',
            data: searchParams.toString(),
            beforeSend: function() {
                $('.loading-spinner').show();
            },
            success: function(response) {
                $('#products-container').html(response.products);
                
                // Update URL without page reload
                const newUrl = window.location.pathname + '?' + searchParams.toString();
                window.history.pushState({}, '', newUrl);
            },
            complete: function() {
                $('.loading-spinner').hide();
            }
        });
    }

    // Filter checkbox change
    $('.filter-checkbox, .color-filter-input').on('change', function() {
        applyFilters();
    });

    // Price range filter
    $('input[name="min_price"], input[name="max_price"]').on('change', function() {
        applyFilters();
    });

    // Sort select change
    $('#sort-select').on('change', function() {
        applyFilters();
    });

    // Clear filters
    $('#clear-filters').on('click', function() {
        $('#filter-form')[0].reset();
        $('#sort-select').val('latest');
        window.location.href = '{{ route("products.index") }}';
    });

    // Pagination links
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function() {
                $('.loading-spinner').show();
            },
            success: function(response) {
                $('#products-container').html(response.products);
                window.history.pushState({}, '', url);
                
                // Scroll to top of products
                $('html, body').animate({
                    scrollTop: $('#products-container').offset().top - 100
                }, 300);
            },
            complete: function() {
                $('.loading-spinner').hide();
            }
        });
    });
});
</script>
@endpush