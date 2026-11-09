<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'formatted_id' => $u->formatted_id,
                'name'         => $u->name,
                'email'        => $u->email,
                'role'         => ucfirst($u->role ?? 'user'),
                'address'      => $u->address,
                'phone'        => $u->phone,
                'avatar_url'   => $u->avatar_url,
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

        // Ambil riwayat pesanan jika tabel orders tersedia
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

