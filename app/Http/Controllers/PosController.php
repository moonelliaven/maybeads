<?php

namespace App\Http\Controllers;

use App\Models\CashierTransaction;
use App\Models\CashierTransactionItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Tampilan utama aplikasi Kasir (POS)
     */
    public function index(Request $request)
    {
        $categories = Category::all(['id', 'category_name']);

        $query = Product::with(['category', 'variants'])->where('status', 'aktif');

        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where('product_name', 'like', "%{$search}%");
        }

        $products = $query->latest('id')->get()->map(function ($p) {
            return [
                'id'           => $p->id,
                'name'         => $p->product_name,
                'category'     => $p->category?->category_name ?? 'Aksesoris',
                'category_id'  => $p->category_id,
                'price'        => (float) $p->price,
                'stock'        => (int) $p->stock,
                'image'        => $p->image ? (str_starts_with($p->image, 'http') ? $p->image : asset('storage/product/' . $p->image)) : asset('images/logo.svg'),
                'variants'     => $p->variants->map(fn($v) => [
                    'id'    => $v->id,
                    'color' => $v->color,
                    'size'  => $v->size,
                    'label' => trim(($v->color ?? '') . ' ' . ($v->size ?? '')),
                ]),
            ];
        });

        // 10 Transaksi kasir terbaru
        $recentTransactions = CashierTransaction::with('items')
            ->latest('id')
            ->take(10)
            ->get();

        return view('admin.pos.index', compact('categories', 'products', 'recentTransactions'));
    }

    /**
     * Proses checkout transaksi kasir secara real-time
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'nullable|exists:products,id',
            'items.*.name'    => 'nullable|string|max:255',
            'items.*.price'   => 'nullable|numeric|min:0',
            'items.*.qty'     => 'required|integer|min:1',
            'items.*.variant' => 'nullable|string',
            'payment_method'  => 'required|string|in:cash,qris,debit,transfer',
            'cash_paid'       => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount'      => 'nullable|numeric|min:0',
            'customer_name'   => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
        ]);

        $cashierTransaction = null;

        DB::transaction(function () use ($validated, &$cashierTransaction) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                if (!empty($item['id'])) {
                    $product = Product::lockForUpdate()->findOrFail($item['id']);

                    if ($item['qty'] > $product->stock) {
                        throw new \Exception("Stok {$product->product_name} tidak mencukupi (Tersedia: {$product->stock}).");
                    }

                    $price = (float) $product->price;
                    $lineSubtotal = $price * $item['qty'];
                    $subtotal += $lineSubtotal;

                    $itemsData[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->product_name,
                        'variant_info' => $item['variant'] ?? null,
                        'price'        => $price,
                        'quantity'     => $item['qty'],
                        'subtotal'     => $lineSubtotal,
                    ];

                    // Kurangi stok produk secara real-time
                    $product->decrement('stock', $item['qty']);
                    $product->increment('order_count', $item['qty']);
                } else {
                    $price = (float) ($item['price'] ?? 0);
                    $lineSubtotal = $price * $item['qty'];
                    $subtotal += $lineSubtotal;

                    $itemsData[] = [
                        'product_id'   => null,
                        'product_name' => $item['name'] ?? 'Penjualan Manual Kasir',
                        'variant_info' => $item['variant'] ?? null,
                        'price'        => $price,
                        'quantity'     => $item['qty'],
                        'subtotal'     => $lineSubtotal,
                    ];
                }
            }

            $discount = (float) ($validated['discount_amount'] ?? 0);
            $tax = (float) ($validated['tax_amount'] ?? 0);
            $totalAmount = max(0, $subtotal - $discount + $tax);

            $cashPaid = (float) ($validated['cash_paid'] ?? 0);
            $changeAmount = 0;

            if ($validated['payment_method'] === 'cash') {
                $cashPaid = max($cashPaid, $totalAmount);
                $changeAmount = max(0, $cashPaid - $totalAmount);
            } else {
                $cashPaid = $totalAmount;
                $changeAmount = 0;
            }

            $txCode = 'POS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $cashierTransaction = CashierTransaction::create([
                'transaction_code' => $txCode,
                'cashier_id'       => Auth::id() ?? 1,
                'customer_name'    => $validated['customer_name'] ?? 'Pelanggan Toko',
                'subtotal'         => $subtotal,
                'discount_amount'  => $discount,
                'tax_amount'       => $tax,
                'total_amount'     => $totalAmount,
                'payment_method'   => $validated['payment_method'],
                'cash_paid'        => $cashPaid,
                'change_amount'    => $changeAmount,
                'payment_status'   => 'lunas',
                'notes'            => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $data) {
                $data['cashier_transaction_id'] = $cashierTransaction->id;
                CashierTransactionItem::create($data);
            }
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'     => true,
                'message'     => 'Transaksi kasir berhasil disimpan.',
                'transaction' => $cashierTransaction->load('items'),
            ]);
        }

        return redirect()->route('admin.pos.index')
            ->with('success', 'Transaksi kasir berhasil diselesaikan!');
    }

    /**
     * Tampilkan detail atau struk cetak transaksi kasir
     */
    public function show($id)
    {
        $transaction = CashierTransaction::with(['items', 'cashier'])->findOrFail($id);

        return view('admin.pos.show', compact('transaction'));
    }

    /**
     * Riwayat transaksi dari kasir (bisa disortir hari ini, per bulan, atau keseluruhan)
     */
    public function transactions(Request $request)
    {
        $filter    = strtolower($request->query('filter', 'semua'));
        $search    = trim($request->query('search', ''));
        $sort      = $request->query('sort', 'terbaru');
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        $query = CashierTransaction::with(['items', 'cashier']);

        // Filter Pencarian
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('cashier', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Rentang Tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }

        // Filter Tab Cepat
        if ($filter === 'hari_ini') {
            $query->whereDate('created_at', today());
        } elseif ($filter === 'bulan_ini') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        // Sortir
        switch ($sort) {
            case 'terlama':
                $query->oldest('id');
                break;
            case 'nominal_tinggi':
                $query->orderByDesc('total_amount');
                break;
            case 'nominal_rendah':
                $query->orderBy('total_amount');
                break;
            case 'terbaru':
            default:
                $query->latest('id');
                break;
        }

        $perPage = $request->input('show_all') === 'true' ? 1000 : 10;
        $transactions = $query->paginate($perPage)->withQueryString();
        $totalTransactions = CashierTransaction::count();

        // Hitung statistik ringkasan kasir
        $todayTotal = CashierTransaction::whereDate('created_at', today())->sum('total_amount');
        $monthTotal = CashierTransaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $allTotal = CashierTransaction::sum('total_amount');

        $stats = [
            'today'       => $todayTotal,
            'month'       => $monthTotal,
            'all'         => $allTotal,
            'today_count' => CashierTransaction::whereDate('created_at', today())->count(),
            'month_count' => CashierTransaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'all_count'   => $totalTransactions,
        ];

        return view('admin.pos.transactions', compact(
            'transactions',
            'filter',
            'stats',
            'totalTransactions',
            'search',
            'sort',
            'startDate',
            'endDate'
        ));
    }
}
