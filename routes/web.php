<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');

});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [HomeController::class, 'collections'])->name('collections');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');