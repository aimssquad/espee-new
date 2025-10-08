<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\HomeController;
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
use App\Http\Controllers\Admin\ExcelUploadController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// SEO-friendly shape routes
Route::get('/products/shape/{shape}', [ProductController::class, 'index'])->name('products.shape');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
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

    // Product Highlights (simple add/delete)
    Route::post('products/{product}/highlights', [\App\Http\Controllers\Admin\ProductHighlightController::class, 'store'])->name('products.highlights.store');
    Route::delete('highlights/{highlight}', [\App\Http\Controllers\Admin\ProductHighlightController::class, 'destroy'])->name('products.highlights.destroy');

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

    // Video Settings
    Route::resource('video-settings', \App\Http\Controllers\Admin\VideoSettingController::class);

    // Excel Upload
    Route::get('excel-upload', [ExcelUploadController::class, 'index'])->name('excel-upload.index');
    Route::get('excel-upload/template', [ExcelUploadController::class, 'downloadTemplate'])->name('excel-upload.template');
    Route::post('excel-upload', [ExcelUploadController::class, 'upload'])->name('excel-upload.upload');

    // Tax Master
    Route::resource('tax-master', \App\Http\Controllers\Admin\TaxMasterController::class);
    Route::post('tax-master/{taxMaster}/toggle-status', [\App\Http\Controllers\Admin\TaxMasterController::class, 'toggleStatus'])->name('tax-master.toggle-status');
    Route::post('tax-master/test-calculation', [\App\Http\Controllers\Admin\TaxMasterController::class, 'testCalculation'])->name('tax-master.test-calculation');

    // Payment Methods
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::get('payment-methods/{paymentMethod}/credentials', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'showCredentials'])->name('payment-methods.credentials');
    Route::post('payment-methods/{paymentMethod}/credentials', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'updateCredentials'])->name('payment-methods.credentials.update');
    Route::post('payment-methods/{paymentMethod}/toggle-status', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    Route::post('payment-methods/reorder', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'reorder'])->name('payment-methods.reorder');
});

// User Panel Routes
Route::middleware(['auth'])->prefix('my-account')->name('user-panel.')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\UserPanelController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [\App\Http\Controllers\UserPanelController::class, 'orderDetails'])->name('order-details');
    Route::get('/addresses', [\App\Http\Controllers\UserPanelController::class, 'addresses'])->name('addresses');
    Route::get('/addresses/create', [\App\Http\Controllers\UserPanelController::class, 'createAddress'])->name('addresses.create');
    Route::post('/addresses', [\App\Http\Controllers\UserPanelController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [\App\Http\Controllers\UserPanelController::class, 'editAddress'])->name('addresses.edit');
    Route::put('/addresses/{address}', [\App\Http\Controllers\UserPanelController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [\App\Http\Controllers\UserPanelController::class, 'deleteAddress'])->name('addresses.delete');
    Route::post('/addresses/{address}/set-default', [\App\Http\Controllers\UserPanelController::class, 'setDefaultAddress'])->name('addresses.set-default');
    Route::get('/profile', [\App\Http\Controllers\UserPanelController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\UserPanelController::class, 'updateProfile'])->name('profile.update');
});
