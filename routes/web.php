<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserLandingController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

//Landing Page Routes
Route::get('/',[UserLandingController::class , 'index'])->name('home');

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


Route::middleware('isAdmin')->group(function () {
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
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, "index"])->name('admin.users.index');
        Route::post('/', [UserController::class, "store"])->name('admin.users.store');
        Route::delete('/{user}', [UserController::class, "destroy"])->name('admin.users.destroy');
        Route::put('/{user}', [UserController::class, "update"])->name('admin.users.update');
    });

    //order route
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersController::class, "index"])->name('admin.orders.index');
        Route::patch('/{order}/status', [OrdersController::class, "updateStatus"])->name('admin.orders.updateStatus');
    });

    //order histori
    // Route::prefix('order-historis')->group(function () {
    //     Route::get('/', [OrderHistoryController::class, "history"])->name('admin.order-historis.index');
    //     Route::get('update-status/{id}/{status}', [OrderHistoryController::class, 'updateStatus'])->name('update-status');
    // });
});
