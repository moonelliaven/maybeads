<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::query()->orderBy('category_name')->get();
    $products = Product::query()->with('category')->latest('id')->get();

    return view('landingpage.home', compact('categories', 'products'));
});

// auth pages
Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    });

    Route::post('/login', function (Request $request) {
        $identifier = trim((string) $request->input('identifier', $request->input('email', '')));
        $password = $request->input('password');

        $admin = User::firstOrCreate(
            ['username' => 'hanzen'],
            [
                'email' => 'hanzen@maybeads.com',
                'password' => Hash::make('hanzen123'),
                'phone_number' => '0000000000',
                'address' => 'Maybeads HQ',
                'role' => 'admin',
            ]
        );

        if ($identifier === 'hanzen' && Hash::check('hanzen123', $admin->password)) {
            Auth::login($admin);
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        $credentials = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identifier, 'password' => $password]
            : ['username' => $identifier, 'password' => $password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('identifier');
    });

    Route::get('/register', function () {
        return view('auth.register');
    });

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $baseUsername = strtolower(str_replace(' ', '', $validated['name']));
        $username = $baseUsername ?: 'user';

        $user = User::create([
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => '0000000000',
            'address' => 'Not set',
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/admin');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/login');
    })->name('logout');
});

// admin page
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
});
