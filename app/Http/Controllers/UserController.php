<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'max:100'],
            'photo_profile' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'role' => ['required', 'string', 'max:20'],
        ]);

        return User::create($validated);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['sometimes', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'email' => ['sometimes', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'string', 'max:100'],
            'photo_profile' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['sometimes', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'role' => ['sometimes', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return $user;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
