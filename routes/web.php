<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Socialite\ProviderController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Models\Feature;
use App\Models\Product;

Route::get('/auth/{provider}', ProviderController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// --- 3. PUBLIC ROUTES ---

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place.order');
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/collection', [CollectionController::class, 'index'])->name('collection');

Route::get('/products', [ProductsController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product}', [ProductsController::class, 'show'])
    ->name('products.show');

// Wishlist routes
Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

// Review routes
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// --- 1. GUEST ROUTES ---
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

    // Forgot password with OTP
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/forgot-password/verify', [AuthController::class, 'showVerifyPasswordReset'])->name('password.verify');
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyPasswordResetOtp'])->name('password.verify-otp');
    Route::post('/forgot-password/resend-otp', [AuthController::class, 'resendPasswordResetOtp'])->name('password.resend-otp');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

//  AUTHENTICATED USER ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/verify-sms', fn () => view('auth.verify-sms'))->name('verify-sms');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
    
    Route::get('/dashboard', [AuthController::class, 'user_dashboard'])->name('dashboard');
    Route::get('/dashboard/orders', [AuthController::class, 'orders'])->name('orders.index');
    Route::get('/dashboard/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/dashboard/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/dashboard/deactivate', [AuthController::class, 'deactivateAccount'])->name('account.deactivate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Cloudinary test
Route::get('/cloudinary-test', fn () => [
    'cloudinary_url' => config('cloudinary.cloud_url'),
    'env_cloudinary_url' => env('CLOUDINARY_URL'),
]);
