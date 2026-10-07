<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware('guest')->group(function () {
    // route::action('/path', [Controller::class, 'method'])->name('namevalue');
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('landing.home');
})->name('home');

// Admin Routes (Hanya dapat diakses oleh user dengan session role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // deafult path admin page
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // route::action('path', function () {
    //  return view('filepath');
    // })->name('namevalue');
});
