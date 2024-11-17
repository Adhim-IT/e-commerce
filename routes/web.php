<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

//Landing Page Routes
Route::get('/', function () {
    return view('landing.landing-page');
})->name('home');

Route::get('/cart', function () {
    return view('landing.shopping-cart');
})->name('cart');

//Auth Routes
Route::get('/auth/login', [AuthController::class, "showLoginForm"])->name('login');
Route::post('/auth/login', [AuthController::class, "login"])->name('auth.login');
Route::get('/auth/register', [AuthController::class, "showRegisterForm"])->name('register');

Route::post('/auth/register', [AuthController::class, "register"])->name('auth.register');

Route::get('/search', function () {
    return view('search');
});
Route::get('/products', function () {
    return view('products');
});

// Admin Routes
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/products', [ProductController::class, "index"])->name('admin.products');
