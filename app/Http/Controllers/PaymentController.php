<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller {
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'order.product', 'paymentMethod', 'deliveryMethod']);

        // 1. Search Query (ID, IDP, Username, Method, Status, Amount)
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

        // 2. Status Filter Tabs (Semua, Selesai, Gagal, Pending)
        $status = strtolower(trim((string) $request->input('status', 'semua')));
        if ($status !== 'semua' && !empty($status)) {
            if ($status === 'selesai' || $status === 'sukses') {
                $query->whereIn('payment_status', ['Sukses', 'sukses', 'completed', 'paid', 'Selesai']);
            } elseif ($status === 'gagal' || $status === 'dibatalkan') {
                $query->whereIn('payment_status', ['Gagal', 'gagal', 'failed', 'Dibatalkan', 'dibatalkan', 'cancelled']);
            } elseif ($status === 'pending' || $status === 'proses') {
                $query->whereIn('payment_status', ['Pending', 'pending', 'Proses', 'proses']);
            } else {
                $query->where('payment_status', 'like', "%{$status}%");
            }
        }

        // 3. Per Page setting (supports "ALL" / "Show Less")
        $perPage = $request->input('show_all') === 'true' ? 100 : 7;
        $totalPayments = Payment::count();
        $payments = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

        $stats = [
            'total_income' => Payment::whereIn('payment_status', ['sukses', 'Sukses', 'lunas'])->sum('amount'),
            'total_count'  => $totalPayments,
            'sukses'       => Payment::whereIn('payment_status', ['sukses', 'Sukses'])->count(),
            'pending'      => Payment::whereIn('payment_status', ['pending', 'Pending'])->count(),
            'gagal'        => Payment::whereIn('payment_status', ['gagal', 'Gagal', 'Dibatalkan', 'dibatalkan'])->count(),
        ];

        return view('admin.payment.index', compact(
            'payments',
            'totalPayments',
            'stats',
            'status',
            'search',
            'perPage'
        ));
    }

    public function show(Request $request, Payment $payment)
    {
        $payment->load(['order.user', 'order.product', 'paymentMethod', 'deliveryMethod']);

        if ($request->wantsJson() || $request->ajax() || !$request->hasHeader('accept') || !view()->exists('admin.payment.show')) {
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
        $paidAt = in_array($st, ['sukses', 'selesai', 'lunas']) ? now() : null;

        $payment->update([
            'payment_status' => $validated['payment_status'],
            'paid_at'        => $paidAt,
        ]);

        if ($payment->order) {
            $payment->order->update([
                'payment_status' => in_array($st, ['sukses', 'selesai', 'lunas']) ? 'lunas' : 'menunggu_pembayaran',
            ]);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payment.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
