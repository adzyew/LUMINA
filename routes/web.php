<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Socialite\ProviderController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Models\Feature;
use App\Models\Product;

Route::get('/auth/{provider}', ProviderController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');
Route::post('/webhooks/paymongo', [PaymentController::class, 'paymongoWebhook'])->name('webhooks.paymongo');

// OTP verification routes (session-based, no auth required)
Route::get('/verify-sms', [AuthController::class, 'showVerifySms'])
    ->middleware('throttle:otp-view')
    ->name('verify-sms');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
    ->middleware('throttle:otp-verify')
    ->name('verify.otp');
Route::post('/verify-firebase-sms', [AuthController::class, 'verifyFirebaseSms'])
    ->middleware('throttle:otp-verify')
    ->name('verify.firebase.sms');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
    ->middleware('throttle:otp-resend')
    ->name('otp.resend');
Route::post('/otp/fallback-email', [AuthController::class, 'fallbackToEmailOtp'])
    ->middleware('throttle:otp-resend')
    ->name('otp.fallback.email');

// --- GUEST ROUTES ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->middleware('throttle:auth-login')->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'registerPost'])->middleware('throttle:auth-register')->name('register.post');

    // Forgot password with OTP
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:password-otp-send')->name('password.email');
    Route::get('/forgot-password/verify', [AuthController::class, 'showVerifyPasswordReset'])->name('password.verify');
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyPasswordResetOtp'])->middleware('throttle:password-otp-verify')->name('password.verify-otp');
    Route::post('/forgot-password/resend-otp', [AuthController::class, 'resendPasswordResetOtp'])->middleware('throttle:password-otp-resend')->name('password.resend-otp');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-update')->name('password.update');
});

// --- CUSTOMER ROUTES (guests allowed, but admin/staff are redirected to their dashboard) ---
Route::middleware('customer')->group(function () {
    // Public browsing
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/collection', [CollectionController::class, 'index'])->name('collection');
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

    // Wishlist toggle (public)
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Authenticated customer-only routes
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/confirmation/{order}', [CartController::class, 'confirmation'])->name('checkout.confirmation');
        Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place.order');
        Route::get('/payments/paymongo/success', [PaymentController::class, 'paymongoSuccess'])->name('payments.paymongo.success');
        Route::get('/payments/paymongo/cancel', [PaymentController::class, 'paymongoCancel'])->name('payments.paymongo.cancel');
        
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::get('/my-wishlist', [WishlistController::class, 'storefront'])->name('wishlist.storefront');

        Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');

        Route::get('/dashboard', [AuthController::class, 'user_dashboard'])->name('dashboard');
        Route::get('/dashboard/orders', [AuthController::class, 'orders'])->name('orders.index');
        Route::get('/dashboard/orders/{order}', [AuthController::class, 'showOrder'])->name('orders.show');
        Route::get('/dashboard/orders/{order}/invoice', [AuthController::class, 'downloadOrderInvoice'])->name('orders.invoice');
        Route::get('/dashboard/profile', [AuthController::class, 'showProfile'])->name('profile.show');
        Route::get('/dashboard/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
        Route::put('/dashboard/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard/help-support', [AuthController::class, 'helpSupport'])->name('support.index');
        Route::post('/dashboard/deactivate', [AuthController::class, 'deactivateAccount'])->name('account.deactivate');
    });
});

// --- LEGAL & CUSTOMER CARE PAGES ---
Route::view('/shipping-information', 'legal.shipping')->name('legal.shipping');
Route::view('/returns-exchange', 'legal.returns')->name('legal.returns');
Route::view('/lifetime-warranty', 'legal.warranty')->name('legal.warranty');
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');
Route::view('/terms-of-service', 'legal.terms')->name('legal.terms');

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/debug-env', function () {
    return [
        'app_env' => app()->environment(),
        'app_env_raw' => config('app.env'),
    ];
});
