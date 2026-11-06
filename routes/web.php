<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Authentication ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login',    [LoginController::class, 'login']);

    Route::get('/register',  [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Landing Page ─────────────────────────────────────────────────────────────
Route::get('/', fn () => view('landing.home'))->name('home');

// ─── Public / User Catalog & Cart ─────────────────────────────────────────────
Route::prefix('main')->name('main.')->group(function () {
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('/cart',      [MainController::class, 'cart'])->name('cart');
});

Route::get('/cart', [MainController::class, 'cart'])->name('cart.index');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

    // Products
    Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function () {
        Route::get('/',               'index')->name('index');
        Route::get('/create',         'create')->name('create');
        Route::post('/',              'store')->name('store');
        Route::get('/{product}',      'show')->name('show');
        Route::get('/{product}/edit', 'edit')->name('edit');
        Route::put('/{product}',      'update')->name('update');
        Route::delete('/{product}',   'destroy')->name('destroy');
    });

    // Categories
    Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function () {
        Route::get('/',               'index')->name('index');
        Route::get('/create',         'create')->name('create');
        Route::post('/',              'store')->name('store');
        Route::get('/{category}',     'show')->name('show');
        Route::get('/{category}/edit','edit')->name('edit');
        Route::put('/{category}',     'update')->name('update');
        Route::delete('/{category}',  'destroy')->name('destroy');
    });

    // Orders
    Route::prefix('orders')->name('order.')->controller(OrderController::class)->group(function () {
        Route::get('/',            'index')->name('index');
        Route::get('/{order}',     'show')->name('show');
        Route::put('/{order}',     'update')->name('update');
        Route::delete('/{order}',  'destroy')->name('destroy');
    });

    // Payments
    Route::prefix('payments')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::get('/',              'index')->name('index');
        Route::get('/{payment}',     'show')->name('show');
        Route::put('/{payment}',     'update')->name('update');
        Route::delete('/{payment}',  'destroy')->name('destroy');
    });

    // User Management
    Route::prefix('users')->name('user.')->controller(UserController::class)->group(function () {
        Route::get('/',           'index')->name('index');
        Route::post('/',          'store')->name('store');
        Route::put('/{user}',     'update')->name('update');
        Route::delete('/{user}',  'destroy')->name('destroy');
    });

    // Point of Sale (Kasir)
    Route::prefix('pos')->name('pos.')->controller(PosController::class)->group(function () {
        Route::get('/',              'index')->name('index');
        Route::get('/transactions',  'index')->name('transactions');
        Route::post('/checkout',     'checkout')->name('checkout');
    });
});
