<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/verify', [AuthController::class, 'verifyRegistration'])->name('register.verify');
    Route::post('/register/resend-code', [AuthController::class, 'resendVerificationCode'])->name('register.resend');

    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
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

    // Sub-rute admin product & produk
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\AdminProductController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}', [\App\Http\Controllers\AdminProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [\App\Http\Controllers\AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [\App\Http\Controllers\AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [\App\Http\Controllers\AdminProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\AdminProductController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}', [\App\Http\Controllers\AdminProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [\App\Http\Controllers\AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [\App\Http\Controllers\AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [\App\Http\Controllers\AdminProductController::class, 'destroy'])->name('destroy');
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
