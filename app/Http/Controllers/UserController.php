<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc')->get()->map(function ($u) {
            return [
                'id'           => $u->id,
                'formatted_id' => $u->formatted_id ?? str_pad((string)$u->id, 5, '0', STR_PAD_LEFT),
                'name'         => $u->name,
                'email'        => $u->email,
                'role'         => ucfirst($u->role ?? 'user'),
                'address'      => $u->address ?? '-',
                'phone'        => $u->phone ?? '-',
                'avatar_url'   => $u->avatar_url ?? null,
                'created_at'   => $u->created_at ? $u->created_at->format('d M Y') : '-',
            ];
        });

        return view('admin.user.index', compact('users'));
    }

    /**
     * Menampilkan detail data user yang dipilih
     */
    public function detail($id)
    {
        $user = User::findOrFail($id);

        $orders = collect();
        $totalOrders = 0;
        $totalSpent = 0;

        try {
            if (class_exists(Order::class)) {
                $orders = Order::where('user_id', $user->id)
                    ->with('product')
                    ->orderBy('order_at', 'desc')
                    ->get();
                $totalOrders = $orders->count();
                $totalSpent = $orders->sum('subtotal');
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel orders belum siap
        }

        $stats = [
            'total_orders' => $totalOrders,
            'total_spent'  => $totalSpent,
        ];

        return view('admin.user.detail', compact('user', 'orders', 'stats'));
    }

    /**
     * Menyimpan data pengguna baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role'     => 'required|in:admin,user',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'role'  => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::check() && Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User ' . $user->name . ' berhasil dihapus.');
    }
}
