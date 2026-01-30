<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;


Route::get('/', function () {
    return view('welcome');

});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [HomeController::class, 'collections'])->name('collections');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');