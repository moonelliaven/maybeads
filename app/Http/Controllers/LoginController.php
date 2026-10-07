<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // path view
    public function showLogin()
    {
        return view('auth.login');
    }

    // login request
    public function login(Request $request)
    {
        // user validate
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        
        // login path validate if success depends on user rules
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/');
        }
    }

    // logout request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}