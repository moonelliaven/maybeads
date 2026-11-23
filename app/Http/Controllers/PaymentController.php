<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'order.product', 'paymentMethod', 'deliveryMethod']);

        // 1. Search Query
        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $cleanId = ltrim(str_ireplace('IDP', '', $search), '#0');
                if ($cleanId !== '' && is_numeric($cleanId)) {
                    $q->where('id', (int) $cleanId);
                }
                $q->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('payment_status', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Status Filter Tabs
        $status = strtolower(trim((string) $request->input('status', 'semua')));
        if ($status !== 'semua' && !empty($status)) {
            if ($status === 'selesai' || $status === 'sukses') {
                $query->whereIn('payment_status', ['Sukses', 'sukses', 'completed', 'paid', 'Selesai', 'berhasil']);
            } elseif ($status === 'gagal' || $status === 'dibatalkan') {
                $query->whereIn('payment_status', ['Gagal', 'gagal', 'failed', 'Dibatalkan', 'dibatalkan', 'cancelled']);
            } elseif ($status === 'pending' || $status === 'proses') {
                $query->whereIn('payment_status', ['Pending', 'pending', 'Proses', 'proses']);
            } else {
                $query->where('payment_status', 'like', "%{$status}%");
            }
        }

        // 3. Sortir berdasarkan waktu & nominal
        $sortBy = $request->input('sort', 'terbaru');
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'nominal_tinggi':
                $query->orderByDesc('amount');
                break;
            case 'nominal_rendah':
                $query->orderBy('amount', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $perPage = $request->input('show_all') === 'true' ? 100 : 10;
        $totalPayments = Payment::count();
        $payments = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_income' => Payment::whereIn('payment_status', ['sukses', 'Sukses', 'paid', 'lunas', 'berhasil'])->sum('amount'),
            'total_count'  => $totalPayments,
            'sukses'       => Payment::whereIn('payment_status', ['sukses', 'Sukses', 'paid', 'lunas', 'berhasil'])->count(),
            'pending'      => Payment::whereIn('payment_status', ['pending', 'Pending', 'proses', 'Proses'])->count(),
            'gagal'        => Payment::whereIn('payment_status', ['gagal', 'Gagal', 'Dibatalkan', 'dibatalkan'])->count(),
        ];

        return view('admin.payment.index', compact(
            'payments',
            'totalPayments',
            'stats',
            'status',
            'search',
            'sortBy',
            'perPage'
        ));
    }

    public function show(Request $request, Payment $payment)
    {
        $payment->load(['order.user', 'order.product', 'paymentMethod', 'deliveryMethod']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id'               => $payment->formatted_id,
                'user'             => $payment->order?->user?->name ?? 'Customer',
                'amount'           => $payment->formatted_amount,
                'method'           => $payment->payment_method ?? 'QRIS',
                'status'           => $payment->payment_status,
                'date'             => $payment->formatted_date,
                'bukti_pembayaran' => $payment->bukti_pembayaran ? asset('storage/' . $payment->bukti_pembayaran) : null,
                'order_code'       => $payment->order?->order_code ?? ('#MBDS-' . str_pad((string)$payment->order_id, 4, '0', STR_PAD_LEFT)),
            ]);
        }

        return view('admin.payment.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_status' => 'required|string',
        ]);

        $st = strtolower($validated['payment_status']);
        $paidAt = in_array($st, ['sukses', 'selesai', 'lunas', 'berhasil']) ? now() : null;

        $payment->update([
            'payment_status' => $validated['payment_status'],
            'paid_at'        => $paidAt,
        ]);

        if ($payment->order) {
            if (in_array($st, ['sukses', 'selesai', 'lunas', 'berhasil'])) {
                $payment->order->update([
                    'payment_status' => 'sudah_dibayar',
                ]);
            } elseif (in_array($st, ['gagal', 'dibatalkan'])) {
                $payment->order->update([
                    'payment_status' => 'dibatalkan',
                ]);
            }
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payment.index')->with('success', 'Riwayat pembayaran berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $status = strtolower(trim((string) $request->input('status', 'semua')));
        $query = Payment::with(['order.user', 'order.product', 'paymentMethod']);

        if ($status !== 'semua' && !empty($status)) {
            if ($status === 'selesai' || $status === 'sukses') {
                $query->whereIn('payment_status', ['Sukses', 'sukses', 'completed', 'paid', 'Selesai', 'berhasil']);
            } elseif ($status === 'gagal' || $status === 'dibatalkan') {
                $query->whereIn('payment_status', ['Gagal', 'gagal', 'failed', 'Dibatalkan', 'dibatalkan', 'cancelled']);
            } elseif ($status === 'pending' || $status === 'proses') {
                $query->whereIn('payment_status', ['Pending', 'pending', 'Proses', 'proses']);
            }
        }

        $payments = $query->orderBy('id', 'desc')->get();

        $filename = 'rekap_pembayaran_maybeads_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID Pembayaran',
                'ID Pesanan',
                'Nama Pelanggan',
                'Email',
                'Metode Pembayaran',
                'Nominal (Rp)',
                'Status Pembayaran',
                'Waktu Pembayaran'
            ]);

            foreach ($payments as $p) {
                fputcsv($handle, [
                    '#PAY-' . str_pad($p->id, 4, '0', STR_PAD_LEFT),
                    '#ORD-' . str_pad($p->order_id, 4, '0', STR_PAD_LEFT),
                    $p->order?->user?->name ?? 'Guest / Kasir',
                    $p->order?->user?->email ?? '-',
                    $p->payment_method ?? 'QRIS',
                    $p->amount ?? 0,
                    ucfirst($p->payment_status ?? 'Pending'),
                    $p->paid_at ? $p->paid_at->format('Y-m-d H:i') : ($p->created_at ? $p->created_at->format('Y-m-d H:i') : '-')
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
