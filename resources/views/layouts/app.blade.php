<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espee - Premium Sunglasses & Frames')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --accent-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #333333;
            border-color: #333333;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Navbar Dropdown Styles */
        .navbar-nav .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 8px;
            min-width: 200px;
        }

        .navbar-nav .dropdown-item {
            padding: 10px 20px;
            font-size: 0.9rem;
            color: #333;
            transition: all 0.3s ease;
        }

        .navbar-nav .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        .navbar-nav .dropdown-divider {
            margin: 8px 0;
            border-color: #e9ecef;
        }

        .navbar-nav .dropdown-toggle::after {
            margin-left: 8px;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #000;
            transform: translateY(-1px);
        }

        /* Search Icon Styles */
        .search-icon-container {
            position: relative;
        }

        .search-icon-btn {
            background: none;
            border: none;
            color: #000;
            font-size: 1.2rem;
            padding: 8px 12px;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-icon-btn:hover {
            background: #f8f9fa;
            color: #333;
            transform: scale(1.1);
        }

        /* Search Dropdown Styles */
        .search-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 350px;
            z-index: 1000;
            display: none;
            margin-top: 8px;
        }

        .search-dropdown.show {
            display: block;
        }

        .search-dropdown-content {
            padding: 20px;
        }

        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 25px;
            padding: 5px;
            margin-bottom: 15px;
        }

        .live-search-input {
            border: none;
            background: transparent;
            padding: 8px 15px;
            font-size: 0.9rem;
            flex: 1;
            outline: none;
            color: #333;
        }

        .live-search-input::placeholder {
            color: #999;
        }

        .search-submit-btn {
            background: #000;
            border: none;
            color: #fff;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-submit-btn:hover {
            background: #333;
            transform: scale(1.05);
        }

        /* Search Suggestions */
        .search-suggestions {
            display: block;
        }

        .suggestion-header {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .suggestion-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .suggestion-item {
            background: #f8f9fa;
            color: #333;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .suggestion-item:hover {
            background: #000;
            color: #fff;
            transform: translateY(-1px);
        }

        .live-search-results {
            max-height: 200px;
            overflow-y: auto;
            border-top: 1px solid #e9ecef;
            margin-top: 15px;
            padding-top: 15px;
        }

        .live-search-result-item {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .live-search-result-item:hover {
            background: #f8f9fa;
            padding-left: 8px;
        }

        .live-search-result-item:last-child {
            border-bottom: none;
        }

        /* Modern Navbar */
        .navbar {
            background: #fff !important;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #000 !important;
        }

        .navbar-brand:hover {
            color: #333 !important;
        }

        /* Responsive Search */
        @media (max-width: 768px) {
            .search-container {
                margin: 10px 0;
                width: 100%;
            }

            .search-input {
                width: 100%;
            }
        }

        .card {
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .product-card {
            position: relative;
            overflow: hidden;
        }

        .product-card img {
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .price {
            font-weight: bold;
            color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            color: white;
            padding: 80px 0;
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 40px 0;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <strong>ESPEE</strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @foreach($categories as $category)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="category{{ $category->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $category->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="category{{ $category->id }}">
                                @foreach($category->subcategories as $subcategory)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('products.index', ['subcategory' => $subcategory->id]) }}">
                                            {{ $subcategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('products.index', ['category' => $category->id]) }}">
                                        View All {{ $category->name }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Shop All</a>
                    </li>
                </ul>

                <!-- Search Icon -->
                <div class="search-icon-container me-3">
                    <button class="btn search-icon-btn" type="button" id="search-toggle">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- Search Dropdown -->
                <div class="search-dropdown" id="search-dropdown">
                    <div class="search-dropdown-content">
                        <form action="{{ route('products.index') }}" method="GET" id="search-form">
                            <div class="search-input-container">
                                <input class="form-control live-search-input" type="text" name="search" placeholder="Search products..." autocomplete="off" id="live-search">
                                <button class="btn search-submit-btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="search-suggestions" id="search-suggestions">
                            <div class="suggestion-header">Popular Searches</div>
                            <div class="suggestion-items">
                                <div class="suggestion-item" data-search="sunglasses">Sunglasses</div>
                                <div class="suggestion-item" data-search="frames">Frames</div>
                                <div class="suggestion-item" data-search="aviator">Aviator</div>
                                <div class="suggestion-item" data-search="wayfarer">Wayfarer</div>
                                <div class="suggestion-item" data-search="round">Round</div>
                                <div class="suggestion-item" data-search="square">Square</div>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="navbar-nav">
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user-panel.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>My Account
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('user-panel.orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('user-panel.addresses') }}">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Book
                                </a></li>
                                @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-2"></i>Admin Panel
                                </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>ESPEE</strong></h5>
                    <p>Premium sunglasses and frames for the modern lifestyle.</p>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-white-50">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white-50">Cart</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact</h6>
                    <p class="text-white-50">Email: info@espee.com</p>
                    <p class="text-white-50">Phone: +1 (555) 123-4567</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: #666;">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Espee. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let searchTimeout;

            // Toggle search dropdown
            $('#search-toggle').on('click', function(e) {
                e.stopPropagation();
                $('#search-dropdown').toggleClass('show');
                if ($('#search-dropdown').hasClass('show')) {
                    $('#live-search').focus();
                }
            });

            // Close search dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-icon-container, .search-dropdown').length) {
                    $('#search-dropdown').removeClass('show');
                }
            });

            // Handle suggestion clicks
            $('.suggestion-item').on('click', function() {
                const searchTerm = $(this).data('search');
                $('#live-search').val(searchTerm);
                $('#search-form').submit();
            });

            // Live search functionality
            $('#live-search').on('input', function() {
                const query = $(this).val();

                // Clear previous timeout
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    $('#search-suggestions').show();
                    $('.live-search-results').remove();
                    return;
                }

                // Debounce the search
                searchTimeout = setTimeout(function() {
                    performLiveSearch(query);
                }, 300);
            });

            function performLiveSearch(query) {
                $.ajax({
                    url: '{{ route("products.index") }}',
                    method: 'GET',
                    data: { search: query, ajax: true },
                    success: function(response) {
                        displayLiveSearchResults(response, query);
                    },
                    error: function() {
                        console.log('Search error');
                    }
                });
            }

            function displayLiveSearchResults(response, query) {
                // Hide suggestions
                $('#search-suggestions').hide();

                // Remove previous results
                $('.live-search-results').remove();

                // Parse response (assuming it returns HTML with products)
                const $response = $(response);
                const products = $response.find('.product-card');

                if (products.length > 0) {
                    let resultsHtml = '<div class="live-search-results">';
                    resultsHtml += '<div class="suggestion-header">Search Results</div>';

                    products.each(function() {
                        const $product = $(this);
                        const title = $product.find('.card-title').text();
                        const price = $product.find('.price').text();
                        const link = $product.find('a').attr('href');

                        resultsHtml += `
                            <div class="live-search-result-item" onclick="window.location.href='${link}'">
                                <div style="font-weight: 600;">${title}</div>
                                <div style="font-size: 0.8rem; color: #666;">${price}</div>
                            </div>
                        `;
                    });

                    resultsHtml += '</div>';
                    $('#search-dropdown-content').append(resultsHtml);
                } else {
                    let resultsHtml = '<div class="live-search-results">';
                    resultsHtml += '<div class="live-search-result-item">No products found for "' + query + '"</div>';
                    resultsHtml += '</div>';
                    $('#search-dropdown-content').append(resultsHtml);
                }
            }
        });
    </script>

    @stack('scripts')

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <!-- Toasts will be dynamically added here -->
    </div>

    <script>
    // Global toast notification function
    function showToast(type, message, duration = 5000) {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        $('.toast-container').append(toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: duration
        });

        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    </script>
</body>
</html>
