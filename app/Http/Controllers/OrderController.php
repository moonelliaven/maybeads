<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('product')
            ->latest('id')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('product_name')
            ->get();

        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'address' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['quantity'] > $product->stock) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Jumlah melebihi stok produk.']);
        }

        DB::transaction(function () use ($validated, $product) {
            $price = (float) $product->price;
            $subtotal = $price * $validated['quantity'];

            Order::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'subtotal' => $subtotal,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'address' => $validated['address'],
                'phone_number' => $validated['phone_number'] ?? null,
            ]);

            $product->decrement('stock', $validated['quantity']);
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load('product');

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => ['required', 'string', 'max:20'],
            'payment_status' => ['required', 'string', 'max:20'],
            'send_at' => ['nullable', 'date'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
