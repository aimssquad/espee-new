@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">Products Management</h2>
        <p class="text-muted mb-0">Manage your product catalog</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="searchInput"
                           placeholder="Search products by name, model, category..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <span class="text-muted small">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body p-0">
        <div id="productsTable">
            @include('admin.products.partials.products-table', ['products' => $products])
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div class="text-muted small">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
            </div>
            <div id="paginationContainer">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div>Searching products...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const productsTable = document.getElementById('productsTable');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const refreshBtn = document.getElementById('refreshBtn');

    let searchTimeout;

    // Search functionality with debouncing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500); // 500ms delay
    });

    // Refresh button
    refreshBtn.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
    });

    // Search function
    function performSearch() {
        const searchTerm = searchInput.value.trim();

        // Show loading
        loadingOverlay.classList.remove('d-none');

        // Make AJAX request
        fetch(`{{ route('admin.products.index') }}?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            productsTable.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('Error performing search. Please try again.');
        })
        .finally(() => {
            loadingOverlay.classList.add('d-none');
        });
    }

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('.pagination a').href;

            loadingOverlay.classList.remove('d-none');

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                productsTable.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination;
            })
            .catch(error => {
                console.error('Pagination error:', error);
                window.location.href = url; // Fallback to page reload
            })
            .finally(() => {
                loadingOverlay.classList.add('d-none');
            });
        }
    });
});
</script>
@endpush
