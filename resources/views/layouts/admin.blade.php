<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Espee</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #007bff;
        }

        .nav-link.active {
            color: white;
            background-color: rgba(0, 123, 255, 0.2);
            border-left-color: #007bff;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 60px;
        }

        .content-area {
            flex: 1;
        }

        .topbar {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6c757d;
            cursor: pointer;
        }

        .content-area {
            padding: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }

        .stats-card.success {
            border-left-color: #28a745;
        }

        .stats-card.warning {
            border-left-color: #ffc107;
        }

        .stats-card.danger {
            border-left-color: #dc3545;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn-admin {
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            color: white;
        }

        /* Enhanced Pagination Styling */
        .pagination-wrapper .pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .pagination-wrapper .pagination .page-item {
            margin: 0;
        }

        .pagination-wrapper .pagination .page-link {
            border: 1px solid #dee2e6;
            color: #495057;
            background-color: #fff;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            min-width: 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-wrapper .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .pagination-wrapper .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,123,255,0.3);
        }

        .pagination-wrapper .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
        }

        .pagination-wrapper .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }

        .pagination-wrapper .pagination .page-link i {
            font-size: 0.875rem;
        }

        /* Pagination Info Styling */
        .pagination-info {
            font-size: 0.875rem;
        }

        .pagination-info strong {
            color: #495057;
            font-weight: 600;
        }


        /* Footer Styles */
        .admin-footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #ffffff;
            padding: 3rem 0 1rem;
            margin-top: auto;
            border-top: 1px solid #333;
            position: relative;
            z-index: 1;
        }

        .admin-footer h5 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .admin-footer h6 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-footer p {
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .admin-footer .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-footer .footer-links li {
            margin-bottom: 0.75rem;
        }

        .admin-footer .footer-links a {
            color: #b0b0b0;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .admin-footer .footer-links a:hover {
            color: #ffffff;
            transform: translateX(5px);
        }

        .admin-footer .footer-links a i {
            margin-right: 0.5rem;
            width: 16px;
            font-size: 0.8rem;
        }

        .admin-footer .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .admin-footer .footer-brand i {
            color: #007bff;
            margin-right: 0.5rem;
        }

        .admin-footer .footer-divider {
            border-color: #444;
            margin: 2rem 0 1rem;
        }

        .admin-footer .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #444;
        }

        .admin-footer .footer-bottom p {
            margin: 0;
            color: #888;
            font-size: 0.85rem;
        }

        .admin-footer .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .admin-footer .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #333;
            color: #b0b0b0;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .admin-footer .social-links a:hover {
            background: #007bff;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .admin-footer .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .admin-footer .contact-info i {
            margin-right: 0.75rem;
            width: 16px;
            color: #007bff;
        }

        .admin-footer .quick-stats {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .admin-footer .quick-stats h6 {
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .admin-footer .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-footer .stat-item:last-child {
            border-bottom: none;
        }

        .admin-footer .stat-label {
            color: #b0b0b0;
            font-size: 0.85rem;
        }

        .admin-footer .stat-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .scroll-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .scroll-to-top.show {
            display: flex;
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            .admin-footer {
                padding: 2rem 0 1rem;
                margin-top: 2rem;
            }

            .admin-footer .social-links {
                justify-content: center;
                margin-top: 1.5rem;
            }

            .admin-footer .quick-stats {
                margin-top: 1.5rem;
            }

            .scroll-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            /* Responsive Pagination */
            .pagination-wrapper .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-wrapper .pagination .page-link {
                padding: 0.375rem 0.5rem;
                min-width: 35px;
                font-size: 0.875rem;
            }

            .pagination-info {
                text-align: center;
                margin-bottom: 1rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: center !important;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-shield-alt me-2"></i>
                <span>Espee Admin</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subcategories.index') }}" class="nav-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Subcategories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Coupons</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.video-settings.index') }}" class="nav-link {{ request()->routeIs('admin.video-settings.*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i>
                        <span>Video Banner</span>
                    </a>
                </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.payment-methods.index') }}" class="nav-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Payment Methods</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.tax-master.index') }}" class="nav-link {{ request()->routeIs('admin.tax-master.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Tax Master</span>
                        </a>
                    </li>

                <li class="nav-item">
                    <a href="{{ route('admin.colors.index') }}" class="nav-link {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                        <i class="fas fa-palette"></i>
                        <span>Colors</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.shapes.index') }}" class="nav-link {{ request()->routeIs('admin.shapes.*') ? 'active' : '' }}">
                        <i class="fas fa-shapes"></i>
                        <span>Shapes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-store"></i>
                        <span>View Store</span>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="sidebar-toggle me-3" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0">@yield('page-title', 'Admin Panel')</h4>
                </div>

                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Welcome, {{ Auth::user()->name }}</span>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-store me-2"></i>View Store</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
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
        </div>

        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container">
                <div class="row">
                    <!-- Company Info -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-brand">
                            <i class="fas fa-shield-alt"></i>
                            ESPEE Admin
                        </div>
                        <p>Professional admin panel for managing your e-commerce store. Built with Laravel and Bootstrap for optimal performance and user experience.</p>

                        <div class="social-links">
                            <a href="#" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6>Quick Links</h6>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.index') }}">
                                    <i class="fas fa-box"></i>
                                    Products
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    Orders
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users"></i>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-store"></i>
                                    View Store
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Management -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6>Management</h6>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('admin.categories.index') }}">
                                    <i class="fas fa-list"></i>
                                    Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.subcategories.index') }}">
                                    <i class="fas fa-tags"></i>
                                    Subcategories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.coupons.index') }}">
                                    <i class="fas fa-ticket-alt"></i>
                                    Coupons
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.payment-methods.index') }}">
                                    <i class="fas fa-credit-card"></i>
                                    Payment Methods
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tax-master.index') }}">
                                    <i class="fas fa-receipt"></i>
                                    Tax Master
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact & Stats -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h6>Contact Information</h6>
                        <div class="contact-info">
                            <i class="fas fa-envelope"></i>
                            <span>admin@espee.com</span>
                        </div>
                        <div class="contact-info">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="contact-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Business St, City, State 12345</span>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <h6>Quick Stats</h6>
                            <div class="stat-item">
                                <span class="stat-label">Total Products</span>
                                <span class="stat-value">{{ $stats['total_products'] ?? 0 }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Orders</span>
                                <span class="stat-value">{{ $stats['total_orders'] ?? 0 }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Pending Orders</span>
                                <span class="stat-value">{{ $stats['pending_orders'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="footer-divider">

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} ESPEE Admin Panel. All rights reserved. | Built with Laravel {{ app()->version() }} | Version 1.0.0</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" title="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }

        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');

        // Show/hide scroll to top button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        // Smooth scroll to top when button is clicked
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
