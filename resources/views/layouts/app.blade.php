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

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-container {
                margin: 10px 0;
                width: 100%;
            }

            .search-input {
                width: 100%;
            }

            /* Hero Section Mobile */
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero-btn {
                width: 100%;
                max-width: 300px;
            }

            .hero-stats {
                justify-content: center;
            }

            .stat-item h3 {
                font-size: 2rem;
            }

            .sunglasses-showcase {
                height: 300px;
            }

            .sunglass-item {
                width: 120px;
                height: 120px;
            }

            /* Section Titles Mobile */
            .section-title {
                font-size: 2rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }

            /* Product Cards Mobile */
            .product-card-modern {
                margin-bottom: 2rem;
            }

            .product-actions {
                gap: 0.5rem;
            }

            .action-btn {
                width: 40px;
                height: 40px;
            }

            /* Feature Cards Mobile */
            .feature-card {
                padding: 2rem 1.5rem;
                margin-bottom: 2rem;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .feature-title {
                font-size: 1.3rem;
            }

            /* Category Cards Mobile */
            .category-card {
                height: 300px;
                margin-bottom: 2rem;
            }

            .category-title {
                font-size: 1.5rem;
            }

            .category-description {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .stat-item h3 {
                font-size: 1.8rem;
            }

            .sunglass-item {
                width: 100px;
                height: 100px;
            }

            .feature-card {
                padding: 1.5rem 1rem;
            }

            .category-card {
                height: 250px;
            }

            .category-title {
                font-size: 1.3rem;
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
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: var(--secondary-color);
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="footer-grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="%23fff" opacity="0.03"/><circle cx="75" cy="75" r="0.5" fill="%23fff" opacity="0.03"/><circle cx="50" cy="10" r="0.3" fill="%23fff" opacity="0.05"/><circle cx="10" cy="50" r="0.3" fill="%23fff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23footer-grain)"/></svg>');
            pointer-events: none;
        }

        .footer-brand h4 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 1rem;
        }

        .social-links {
            margin-top: 1.5rem;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .footer-links li a {
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .footer-links li a:hover {
            color: #fff !important;
            transform: translateX(5px);
        }

        .contact-info .contact-item {
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .contact-info .contact-item:hover {
            transform: translateX(5px);
        }

        .newsletter-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .newsletter-form input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 25px;
        }

        .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-form input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        .newsletter-form .btn {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .newsletter-form .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .payment-methods i {
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .payment-methods i:hover {
            transform: scale(1.2);
            color: #fff !important;
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            .footer {
                padding: 40px 0 20px;
            }

            .footer-brand h4 {
                font-size: 1.5rem;
            }

            .newsletter-section {
                padding: 1.5rem;
            }

            .newsletter-form {
                flex-direction: column;
                gap: 1rem;
            }

            .newsletter-form input {
                width: 100%;
            }
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
                <!-- Brand Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand">
                        <h4 class="text-white mb-3">
                            <strong>ESPEE</strong>
                        </h4>
                        <p class="text-white-50 mb-4">
                            Premium sunglasses and frames for the modern lifestyle.
                            Discover our exclusive collection of designer eyewear.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link me-3">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link me-3">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link me-3">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Shop</h6>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="{{ route('products.index') }}" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>All Products
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Men's Collection
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Women's Collection
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('products.index', ['gender' => 'unisex']) }}" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Unisex Collection
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Support</h6>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="#" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Size Guide
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Shipping Info
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>Returns
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50">
                                <i class="fas fa-chevron-right me-2"></i>FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Contact Us</h6>
                    <div class="contact-info">
                        <div class="contact-item mb-3">
                            <i class="fas fa-envelope me-3 text-white"></i>
                            <span class="text-white-50">info@espee.com</span>
                        </div>
                        <div class="contact-item mb-3">
                            <i class="fas fa-phone me-3 text-white"></i>
                            <span class="text-white-50">+1 (555) 123-4567</span>
                        </div>
                        <div class="contact-item mb-3">
                            <i class="fas fa-map-marker-alt me-3 text-white"></i>
                            <span class="text-white-50">123 Fashion Street, Style City, SC 12345</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock me-3 text-white"></i>
                            <span class="text-white-50">Mon-Fri: 9AM-6PM EST</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="newsletter-section">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="text-white mb-2">Stay Updated</h6>
                                <p class="text-white-50 mb-0">Subscribe to our newsletter for exclusive offers and new arrivals.</p>
                            </div>
                            <div class="col-md-6">
                                <form class="newsletter-form d-flex">
                                    <input type="email" class="form-control me-2" placeholder="Enter your email" required>
                                    <button type="submit" class="btn btn-outline-light">
                                        <i class="fas fa-paper-plane me-1"></i>Subscribe
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <hr class="my-4" style="border-color: #666;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">
                        &copy; 2024 ESPEE. All rights reserved. |
                        <a href="#" class="text-white-50 me-2">Privacy Policy</a>
                        <a href="#" class="text-white-50">Terms of Service</a>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="payment-methods">
                        <span class="text-white-50 me-3">We Accept:</span>
                        <i class="fab fa-cc-visa me-2 text-white"></i>
                        <i class="fab fa-cc-mastercard me-2 text-white"></i>
                        <i class="fab fa-cc-paypal me-2 text-white"></i>
                        <i class="fab fa-cc-amex text-white"></i>
                    </div>
                </div>
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
