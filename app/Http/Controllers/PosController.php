<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all(['id', 'category_name']);
        
        $query = Product::with('category')->where('status', 'aktif');

        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where('product_name', 'like', "%{$search}%");
        }

        $products = $query->latest('id')->get();

        // Riwayat transaksi kasir hari ini / terbaru
        $recentTransactions = Order::with(['product', 'payment'])
            ->where('address', 'like', '%Kasir Langsung%')
            ->latest('id')
            ->take(10)
            ->get();

        return view('admin.pos.index', compact('categories', 'products', 'recentTransactions'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'paid_amount'    => 'required|numeric|min:0',
        ]);

        $createdOrders = [];

        DB::transaction(function () use ($validated, &$createdOrders) {
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($item['qty'] > $product->stock) {
                    throw new \Exception("Stok produk {$product->product_name} tidak mencukupi.");
                }

                $price = (float) $product->price;
                $subtotal = $price * $item['qty'];

                $order = Order::create([
                    'user_id'        => Auth::id() ?? 1,
                    'product_id'     => $product->id,
                    'quantity'       => $item['qty'],
                    'subtotal'       => $subtotal,
                    'order_status'   => 'selesai',
                    'payment_status' => 'lunas',
                    'address'        => 'Kasir Langsung (Offline Store)',
                    'order_at'       => now(),
                    'send_at'        => now(),
                ]);

                Payment::create([
                    'order_id'       => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'amount'         => $subtotal,
                    'payment_status' => 'sukses',
                    'paid_at'        => now(),
                ]);

                $product->decrement('stock', $item['qty']);
                $createdOrders[] = $order;
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi kasir berhasil disimpan.',
                'orders'  => $createdOrders,
            ]);
        }

        return redirect()->route('admin.pos.index')
            ->with('success', 'Transaksi kasir berhasil diselesaikan.');
    }
}
