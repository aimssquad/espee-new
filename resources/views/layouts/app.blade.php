<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espee - Premium Sunglasses & Frames')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --gray-light: #f8f9fa;
            --gray-medium: #6c757d;
            --gray-dark: #343a40;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: var(--primary-color);
            background-color: var(--secondary-color);
        }

        .navbar {
            background-color: var(--secondary-color) !important;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.7;
        }

        .btn-dark {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background-color: var(--gray-dark);
            border-color: var(--gray-dark);
        }

        .btn-outline-dark {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 2rem;
            font-weight: 500;
        }

        .btn-outline-dark:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .hero-section {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 100px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: 2px;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .product-card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-card .card-img-top {
            height: 300px;
            object-fit: cover;
            background-color: var(--gray-light);
        }

        .product-card .card-body {
            padding: 1.5rem;
        }

        .product-card .card-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-card .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .color-swatch {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-block;
            margin: 0 3px;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .color-swatch:hover,
        .color-swatch.active {
            transform: scale(1.2);
            border-color: var(--primary-color);
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 3rem 0;
            margin-top: 5rem;
        }

        .footer a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer a:hover {
            opacity: 0.7;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .product-card .card-img-top {
                height: 250px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">ESPEE</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['category' => 'sunglasses']) }}">Sunglasses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index', ['category' => 'frames']) }}">Frames</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">All Products</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form class="d-flex" action="{{ route('products.index') }}" method="GET">
                            <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-dark" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3">ESPEE</h5>
                    <p>Premium sunglasses and frames for the modern lifestyle.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">All Products</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Connect</h6>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Espee. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Loading Spinner -->
    <div class="loading-spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global AJAX Setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'dark' : 'danger'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            const toast = $(toastHtml);
            $('.toast-container').append(toast);
            const bsToast = new bootstrap.Toast(toast[0]);
            bsToast.show();
            
            toast.on('hidden.bs.toast', function () {
                $(this).remove();
            });
        }

        // Update cart count
        function updateCartCount(count) {
            $('#cart-count').text(count);
        }
    </script>

    @stack('scripts')
</body>
</html>