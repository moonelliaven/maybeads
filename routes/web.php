<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
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

// Admin Routes (Hanya dapat diakses oleh user dengan session role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // route but in group; product
    // route::middleware('user')-> prefix('product')
    Route::middleware(['product'])->prefix('product')->name('product.')->group(function () {
        // index
        Route::get('/', function () {
            return view('admin.product.index');
        })->name('index');

        // create
        Route::get('/create', function () {
            return view('admin.product.create');
        })->name('create');

        // edit
        Route::get('/{id}/edit', function () {
            return view('admin.product.edit');
        })->name('edit');

        // delete
        Route::delete('/{id}/delete', function () {
            return view('admin.product.delete');
        })->name('delete');
    });
});
