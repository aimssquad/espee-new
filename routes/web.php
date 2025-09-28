<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\FrontendLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ShapeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\UserController;

// Public routes
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Coupon routes
Route::post('/coupon/validate', [CouponController::class, 'validateCoupon'])->name('coupon.validate');

// Frontend Auth routes
Route::get('/login', [FrontendLoginController::class, 'showLoginForm'])->name('frontend.login');
Route::post('/login', [FrontendLoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [FrontendLoginController::class, 'logout'])->name('logout');

// Admin Auth routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login']);

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Subcategories
    Route::resource('subcategories', SubcategoryController::class);

    // Shapes
    Route::resource('shapes', ShapeController::class);

    // Colors
    Route::resource('colors', ColorController::class);

    // Products
    Route::resource('products', AdminProductController::class);
    Route::get('products/{product}/variants', [AdminProductController::class, 'variants'])->name('products.variants');
    Route::post('products/{product}/variants', [AdminProductController::class, 'addVariant'])->name('products.add-variant');
    Route::put('products/{product}/variants/{variant}', [AdminProductController::class, 'updateVariant'])->name('products.update-variant');
    Route::delete('products/{product}/variants/{variant}', [AdminProductController::class, 'deleteVariant'])->name('products.delete-variant');

    // Product Images
    Route::delete('product-images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::post('product-images/{image}/set-primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Users
    Route::resource('users', UserController::class);
});
