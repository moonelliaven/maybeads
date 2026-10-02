<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('landing.home');
})->name('home');

// Admin Routes (Hanya dapat diakses oleh user dengan session role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Sub-rute admin product
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', function () { return view('admin.product.index'); })->name('index');
        Route::get('/create', function () { return view('admin.product.create'); })->name('create');
        Route::get('/update', function () { return view('admin.product.update'); })->name('update');
        Route::get('/delete', function () { return view('admin.product.delete'); })->name('delete');
    });

    // Pengaturan Website & Akun (Diarahkan ke satu halaman yang sama)
    Route::get('/settings', function (\Illuminate\Http\Request $request) {
        $tab = $request->query('tab', 'website');
        return view('admin.settings', compact('tab'));
    })->name('settings');

    Route::get('/account', function () {
        return redirect()->route('admin.settings', ['tab' => 'account']);
    })->name('account');

    Route::get('/pengaturan', function () {
        return redirect()->route('admin.settings', ['tab' => 'website']);
    })->name('pengaturan');

    Route::get('/akun', function () {
        return redirect()->route('admin.settings', ['tab' => 'account']);
    })->name('akun');
});
