<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::with('category')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

    $categories = Category::orderBy('id')->get();

    return view('landingpage.home', compact('products', 'categories'));
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', function () {
            return view('admin.users.index');
        });

        Route::get('/create', function () {
            return view('admin.users.create');
        });

        Route::get('/{id}/edit', function ($id) {
            return view('admin.users.edit', compact('id'));
        });

        Route::get('/{id}/destroy', function ($id) {
            return view('admin.users.destroy', compact('id'));
        });

    });
});
