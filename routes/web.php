<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Models\Feature;
use App\Models\Product;

// --- 1. GUEST ROUTES (Login/Register/OTP) ---
Route::middleware('guest')->group(function () {
    
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

    // OTP Verification
    Route::get('/verify-sms', function () { 
        return view('auth.verify-sms'); 
    })->name('verify-sms');
    
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    
    // *** FIX IS HERE ***
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
    });

// --- 2. AUTHENTICATED ROUTES (Dashboard/Logout) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'user_dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

// --- 3. PUBLIC ROUTES ---

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// Products Resource
Route::resource('products', ProductController::class);

// Homepage (Consolidated)
Route::get('/', function () {
    $features = Feature::all(); 
    $featuredProducts = Product::take(4)->get(); // Fetches 4 products for the grid
    return view('welcome', compact('features', 'featuredProducts'));
})->name('home');