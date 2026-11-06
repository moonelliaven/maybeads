<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product.category', 'payment']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'semua') {
                $query->where('order_status', $status);
            }
        }

        $orders = $query->latest('id')->paginate(10)->withQueryString();

        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('order_status', 'pending')->count(),
            'proses'     => Order::where('order_status', 'proses')->count(),
            'selesai'    => Order::where('order_status', 'selesai')->count(),
            'dibatalkan' => Order::where('order_status', 'dibatalkan')->count(),
        ];

        return view('admin.order.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'product.category', 'payment']);

        return view('admin.order.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status'   => ['required', 'string'],
            'payment_status' => ['required', 'string'],
            'send_at'        => ['nullable', 'date'],
        ]);

        if ($validated['order_status'] === 'proses' && !$order->send_at) {
            $validated['send_at'] = now();
        }

        $order->update($validated);

        if ($order->payment) {
            $order->payment->update([
                'payment_status' => $validated['payment_status'] === 'lunas' ? 'sukses' : 'pending',
                'paid_at'        => $validated['payment_status'] === 'lunas' ? ($order->payment->paid_at ?? now()) : null,
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
