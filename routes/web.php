<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('landing.home');
})->name('home');

// Admin Routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Product Routes
        Route::prefix('product')
            ->name('product.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'index'])
                    ->name('index');

                Route::get('/create', [ProductController::class, 'create'])
                    ->name('create');

                Route::post('/', [ProductController::class, 'store'])
                    ->name('store');

                Route::get('/{product}/edit', [ProductController::class, 'edit'])
                    ->name('edit');

                Route::put('/{product}', [ProductController::class, 'update'])
                    ->name('update');

                Route::delete('/{product}', [ProductController::class, 'destroy'])
                    ->name('destroy');
            });
    });

