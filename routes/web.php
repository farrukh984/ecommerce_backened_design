<?php

use Illuminate\Support\Facades\Route;

// ─── Auth Controllers ─────────────────────────────────────
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ─── Public Controllers ───────────────────────────────────
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;

// ─── User Controllers ────────────────────────────────────
use App\Http\Controllers\UserDashboardController;

// ─── Admin Controllers ───────────────────────────────────
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConditionController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ProductReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Inquiry & Newsletter
Route::post('/inquiry/send', [InquiryController::class, 'send'])->name('inquiry.send');
Route::post('/newsletter/subscribe', [InquiryController::class, 'subscribe'])->name('newsletter.subscribe');

// Cart & Checkout
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/add/{id}', 'add')->name('cart.add');
    Route::post('/cart/update', 'update')->name('cart.update');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
    Route::post('/cart/save-later/{id}', 'saveForLater')->name('cart.saveLater');
    Route::post('/cart/move-to-cart/{id}', 'moveToCart')->name('cart.moveToCart');
    Route::post('/cart/remove-saved/{id}', 'removeSaved')->name('cart.removeSaved');
    Route::get('/checkout', 'showCheckout')->name('cart.checkout');
    Route::post('/checkout/place-order', 'placeOrder')->name('cart.placeOrder');
    Route::post('/cart/coupon', 'applyCoupon')->name('cart.applyCoupon');
});

// Products & Wishlist
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{id}', 'show')->name('products.show');
    Route::post('/wishlist/toggle/{id}', 'toggleWishlist')->name('wishlist.toggle');
});

// Static Pages
Route::view('/products_listing', 'pages.products')->name('products.listing');
Route::get('/hot-offers', [PageController::class, 'hotOffers'])->name('pages.hotOffers');
Route::get('/gift-boxes', [PageController::class, 'giftBoxes'])->name('pages.giftBoxes');
Route::get('/help', [PageController::class, 'help'])->name('pages.help');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/login', 'loginStore')->name('login.store');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerStore')->name('register.store');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/admin/otp', 'showAdminOtp')->name('admin.otp');
    Route::post('/admin/otp/verify', 'verifyAdminOtp')->name('admin.otp.verify');
});

// Google OAuth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Facebook OAuth
Route::get('/auth/facebook/redirect', [FacebookController::class, 'redirectToFacebook'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard & Analytics
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');

        // CRUD Resources
        Route::resources([
            'brands'     => BrandController::class,
            'categories' => CategoryController::class,
            'conditions' => ConditionController::class,
            'features'   => FeatureController::class,
            'products'   => AdminProductController::class,
            'suppliers'  => SupplierController::class,
        ]);

        // Deals
        Route::resource('deals', DealController::class)->except(['show']);

        // Product Stock & Toggle
        Route::patch('/products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggleActive');
        Route::patch('/products/{product}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.updateStock');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        // Messaging
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [MessageController::class, 'chat'])->name('messages.chat');
        Route::get('/messages/{id}/poll', [MessageController::class, 'getMessages'])->name('messages.poll');
        Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
        Route::post('/messages/typing', [MessageController::class, 'typing'])->name('messages.typing');
        Route::get('/messages/{id}/typing-status', [MessageController::class, 'typingStatus'])->name('messages.typing.status');

        // Reviews
        Route::get('/reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [ProductReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');
    });

/*
|--------------------------------------------------------------------------
| User Dashboard Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_user'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Orders
    Route::get('/dashboard/orders', [UserDashboardController::class, 'orders'])->name('user.orders');
    Route::get('/dashboard/orders/{order}', [UserDashboardController::class, 'orderDetail'])->name('user.orders.show');
    Route::delete('/dashboard/orders/{order}', [UserDashboardController::class, 'deleteOrder'])->name('user.orders.delete');

    // Wishlist
    Route::get('/dashboard/wishlist', [UserDashboardController::class, 'wishlist'])->name('user.wishlist');

    // Profile
    Route::get('/dashboard/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/dashboard/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');

    // Messaging
    Route::get('/dashboard/messages', [MessageController::class, 'index'])->name('user.messages');
    Route::get('/dashboard/messages/{id}', [MessageController::class, 'chat'])->name('user.messages.chat');
    Route::get('/dashboard/messages/{id}/poll', [MessageController::class, 'getMessages'])->name('user.messages.poll');
    Route::post('/dashboard/messages/send', [MessageController::class, 'send'])->name('user.messages.send');
    Route::post('/dashboard/messages/typing', [MessageController::class, 'typing'])->name('user.messages.typing');
    Route::get('/dashboard/messages/{id}/typing-status', [MessageController::class, 'typingStatus'])->name('user.messages.typing.status');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
