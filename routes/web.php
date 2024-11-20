<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

//Landing Page Routes
Route::get('/', function () {
    return view('landing.landing-page');
})->name('home');

Route::get('/cart', function () {
    return view('landing.shopping-cart');
})->name('cart');
Route::get('/search', function () {
    return view('search');
});
Route::get('/products', function () {
    return view('products');
});





//Auth Routes
Route::get('/auth/login', [AuthController::class, "showLoginForm"])->name('login');
Route::post('/auth/login', [AuthController::class, "login"])->name('auth.login');
Route::get('/auth/register', [AuthController::class, "showRegisterForm"])->name('register');
Route::post('/auth/register', [AuthController::class, "register"])->name('auth.register');
Route::post ('/logout', [AuthController::class, "logout"])->name('logout');





// Admin Routes
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
//category
Route::get('/admin/categories',[CategoryController::class, "index"])->name('admin.categories.index');
Route::delete('/admin/categories/{category}', [CategoryController::class, "destroy"])->name('admin.categories.destroy');
Route::post('/admin/categories', [CategoryController::class, "store"])->name('admin.categories.store');
Route::put('/admin/categories/{category}', [CategoryController::class, "update"])->name('admin.categories.update');


//product
Route::get('/admin/products', [ProductController::class, "index"])->name('admin.products');
Route::delete('/admin/products/{product}', [ProductController::class, "destroy"])->name('admin.products.destroy');
Route::post('/admin/products', [ProductController::class, "store"])->name('admin.products.store');
Route::put('/admin/products/{product}', [ProductController::class, "update"])->name('admin.products.update');

//users managament
Route::get( '/admin/users', [UserController::class, "index"])->name ('admin.users');
Route::delete('/admin/users/{user}', [UserController::class, "destroy"])->name('admin.users.destroy');
Route::post('/admin/users', [UserController::class, "store"])->name('admin.users.store');
Route::put('/admin/users/{user}', [UserController::class, "update"])->name('admin.users.update');

