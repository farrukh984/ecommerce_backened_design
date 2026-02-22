<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConditionController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\CartController;
use App\Http\Controllers\InquiryController;

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Inquiry and Newsletter routes
Route::post('/inquiry/send', [InquiryController::class, 'send'])->name('inquiry.send');
Route::post('/newsletter/subscribe', [InquiryController::class, 'subscribe'])->name('newsletter.subscribe');

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

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{id}', 'show')->name('products.show');
    Route::post('/wishlist/toggle/{id}', 'toggleWishlist')->name('wishlist.toggle');
});

// Static / Placeholder routes
Route::view('/products_listing', 'pages.products')->name('products.listing');

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
    // Admin OTP verification views
    Route::get('/admin/otp', 'showAdminOtp')->name('admin.otp');
    Route::post('/admin/otp/verify', 'verifyAdminOtp')->name('admin.otp.verify');
});

// Google OAuth
Route::get('/auth/redirect/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/callback/google', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
// Also accept the common Google callback/redirect paths (matches your .env)
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/


Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('password.otp');

Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.otp.verify');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        
        Route::resources([
            'brands'     => BrandController::class,
            'categories' => CategoryController::class,
            'conditions' => ConditionController::class,
            'features'   => FeatureController::class,
            'products'   => AdminProductController::class,
        ]);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');

        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        // Admin Messaging
        Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [App\Http\Controllers\MessageController::class, 'chat'])->name('messages.chat');
        Route::get('/messages/{id}/poll', [App\Http\Controllers\MessageController::class, 'getMessages'])->name('messages.poll');
        Route::post('/messages/send', [App\Http\Controllers\MessageController::class, 'send'])->name('messages.send');
    });

/*
|--------------------------------------------------------------------------
| User Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/dashboard/orders', [UserDashboardController::class, 'orders'])->name('user.orders');
    Route::get('/dashboard/wishlist', [UserDashboardController::class, 'wishlist'])->name('user.wishlist');
    Route::get('/dashboard/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/dashboard/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');

    // Messaging Routes
    Route::get('/dashboard/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('user.messages');
    Route::get('/dashboard/messages/{id}', [App\Http\Controllers\MessageController::class, 'chat'])->name('user.messages.chat');
    Route::get('/dashboard/messages/{id}/poll', [App\Http\Controllers\MessageController::class, 'getMessages'])->name('user.messages.poll');
    Route::post('/dashboard/messages/send', [App\Http\Controllers\MessageController::class, 'send'])->name('user.messages.send');
});

