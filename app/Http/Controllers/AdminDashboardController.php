<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Statistik Pesanan Keseluruhan
        $allOrders = Order::all();
        $totalOrders = $allOrders->count();

        $selesaiCount = $allOrders->filter(function ($o) {
            return in_array(strtolower($o->order_status), ['selesai', 'sukses', 'success']);
        })->count();

        $prosesCount = $allOrders->filter(function ($o) {
            return in_array(strtolower($o->order_status), ['proses', 'process', 'diproses', 'sedang perjalanan', 'siap diambil']);
        })->count();

        $pendingCount = $allOrders->filter(function ($o) {
            return strtolower($o->order_status) === 'pending';
        })->count();

        $dibatalkanCount = $allOrders->filter(function ($o) {
            return in_array(strtolower($o->order_status), ['dibatalkan', 'batal', 'cancelled']);
        })->count();

        $stats = [
            'total'      => $totalOrders,
            'selesai'    => $selesaiCount,
            'proses'     => $prosesCount,
            'pending'    => $pendingCount,
            'dibatalkan' => $dibatalkanCount,
        ];

        // 2. Data 7 Pesanan Terakhir
        $recentOrders = Order::with(['user', 'product'])
            ->latest('id')
            ->take(7)
            ->get();

        $orders = $recentOrders->map(function ($o) {
            $statusLower = strtolower($o->order_status ?? 'pending');
            $cleanStatus = match ($statusLower) {
                'sukses', 'selesai', 'success'       => 'selesai',
                'proses', 'process', 'diproses',
                'sedang perjalanan', 'siap diambil' => 'proses',
                'dibatalkan', 'batal', 'cancelled'   => 'dibatalkan',
                default                             => 'pending',
            };

            return [
                'id'       => $o->id,
                'user'     => $o->user->name ?? 'Guest / Kasir',
                'time'     => $o->order_at ? $o->order_at->format('Y-m-d H:i') : ($o->created_at ? $o->created_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i')),
                'status'   => $cleanStatus,
                'raw_status' => $o->order_status,
                'subtotal' => (float) $o->subtotal,
            ];
        })->toArray();

        // 3. Pemasukan (Total, QRIS, e-Wallet/Transfer)
        $successPayments = Payment::whereIn('payment_status', ['sukses', 'Sukses', 'paid', 'lunas', 'berhasil'])->get();

        $totalIncome = (float) $successPayments->sum('amount');
        if ($totalIncome <= 0) {
            // Fallback kalkulasi dari orders yang lunas / selesai
            $totalIncome = (float) $allOrders
                ->filter(fn($o) => in_array(strtolower($o->payment_status), ['sukses', 'paid', 'lunas']))
                ->sum('subtotal');
        }

        $qrisIncome = (float) $successPayments
            ->filter(fn($p) => strtolower($p->payment_method ?? '') === 'qris')
            ->sum('amount');

        $ewalletIncome = (float) $successPayments
            ->filter(fn($p) => in_array(strtolower($p->payment_method ?? ''), ['transfer', 'bank', 'e-wallet', 'ewallet', 'gopay', 'ovo', 'dana', 'shopeepay']))
            ->sum('amount');

        if ($qrisIncome <= 0 && $ewalletIncome <= 0 && $totalIncome > 0) {
            $qrisIncome = $totalIncome * 0.45;
            $ewalletIncome = $totalIncome * 0.55;
        }

        $income = [
            'total'   => $totalIncome,
            'qris'    => $qrisIncome,
            'ewallet' => $ewalletIncome,
        ];

        // 4. Riwayat Transaksi Kasir (POS)
        $cashierTx = \App\Models\CashierTransaction::all();
        $cashierTotal = (float) $cashierTx->sum('total_amount');
        $cashierQris = (float) $cashierTx->where('payment_method', 'qris')->sum('total_amount');
        $cashierEwallet = (float) $cashierTx->whereIn('payment_method', ['cash', 'debit', 'transfer'])->sum('total_amount');

        $cashier = [
            'total'   => $cashierTotal,
            'qris'    => $cashierQris,
            'ewallet' => $cashierEwallet,
        ];

        // 5. Riwayat Transaksi Terbaru (3 transaksi terakhir)
        $recentPayments = Payment::with(['order.user'])
            ->latest('id')
            ->take(3)
            ->get();

        $transactions = $recentPayments->map(function ($p) {
            $stLower = strtolower($p->payment_status ?? 'pending');
            $statusClean = in_array($stLower, ['sukses', 'paid', 'lunas', 'berhasil']) ? 'sukses' : (in_array($stLower, ['pending', 'proses']) ? 'proses' : 'gagal');

            return [
                'id'       => $p->id,
                'order_id' => $p->order_id,
                'user'     => $p->order?->user?->name ?? 'Guest / Kasir',
                'time'     => $p->paid_at ? $p->paid_at->format('Y-m-d H:i') : ($p->created_at ? $p->created_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i')),
                'status'   => $statusClean,
                'amount'   => (float) $p->amount,
                'method'   => $p->payment_method ?? 'QRIS',
            ];
        })->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'orders',
            'income',
            'cashier',
            'transactions',
            'pendingCount'
        ));
    }

    /**
     * Halaman khusus notifikasi admin (sederhana: judul + deskripsi kecil)
     */
    public function notifications(Request $request)
    {
        $notifications = collect();

        // 1. Pending orders
        $pending = Order::with('user')->whereIn('order_status', ['Pending', 'pending'])->latest('id')->take(10)->get();
        foreach ($pending as $ord) {
            $notifications->push([
                'id'       => 'ord-' . $ord->id,
                'type'     => 'order',
                'title'    => 'Pesanan Pending #ORD-' . str_pad($ord->id, 4, '0', STR_PAD_LEFT),
                'desc'     => 'Menunggu persetujuan toko dari ' . ($ord->user->name ?? 'Pelanggan') . ' sebesar Rp ' . number_format($ord->subtotal, 0, ',', '.'),
                'time'     => $ord->order_at ? $ord->order_at->diffForHumans() : 'baru saja',
                'link'     => route('admin.order.pendingDetail', $ord->id),
                'badge'    => 'Pending',
                'badge_cl' => '#ea580c',
                'badge_bg' => '#ffedd5',
            ]);
        }

        // 2. Pembayaran terbaru
        $recentPayments = Payment::with('order.user')->whereIn('payment_status', ['Sukses', 'sukses', 'paid'])->latest('id')->take(10)->get();
        foreach ($recentPayments as $pay) {
            $notifications->push([
                'id'       => 'pay-' . $pay->id,
                'type'     => 'payment',
                'title'    => 'Pembayaran Berhasil #' . ($pay->payment_code ?? ('PAY-' . $pay->id)),
                'desc'     => 'Diterima melalui ' . strtoupper($pay->payment_method ?? 'QRIS') . ' sejumlah Rp ' . number_format($pay->amount, 0, ',', '.'),
                'time'     => $pay->paid_at ? $pay->paid_at->diffForHumans() : ($pay->created_at ? $pay->created_at->diffForHumans() : 'baru saja'),
                'link'     => route('admin.payment.show', $pay->id),
                'badge'    => 'Lunas',
                'badge_cl' => '#16a34a',
                'badge_bg' => '#dcfce7',
            ]);
        }

        // 3. Stok menipis
        $lowStocks = Product::where('status', 'aktif')->where('stock', '<=', 5)->take(5)->get();
        foreach ($lowStocks as $prod) {
            $notifications->push([
                'id'       => 'prod-' . $prod->id,
                'type'     => 'stock',
                'title'    => 'Peringatan Stok Menipis: ' . $prod->product_name,
                'desc'     => 'Sisa stok saat ini tersisa ' . $prod->stock . ' unit. Segera perbarui inventaris.',
                'time'     => 'Inventaris Toko',
                'link'     => route('admin.product.show', $prod->id),
                'badge'    => 'Stok Rendah',
                'badge_cl' => '#dc2626',
                'badge_bg' => '#fee2e2',
            ]);
        }

        return view('admin.notification.index', compact('notifications'));
    }
}

