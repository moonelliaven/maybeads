<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'order.product']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('id', 'like', "%{$search}%")
                         ->orWhereHas('user', function ($uq) use ($search) {
                             $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'semua') {
                $query->where('payment_status', $status);
            }
        }

        $payments = $query->latest('id')->paginate(10)->withQueryString();

        $stats = [
            'total_income' => Payment::where('payment_status', 'sukses')->sum('amount'),
            'total_count'  => Payment::count(),
            'sukses'       => Payment::where('payment_status', 'sukses')->count(),
            'pending'      => Payment::where('payment_status', 'pending')->count(),
            'gagal'        => Payment::where('payment_status', 'gagal')->count(),
        ];

        return view('admin.payment.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.product']);

        return view('admin.payment.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:sukses,pending,gagal',
        ]);

        $paidAt = $validated['payment_status'] === 'sukses' ? now() : null;

        $payment->update([
            'payment_status' => $validated['payment_status'],
            'paid_at'        => $paidAt,
        ]);

        if ($payment->order) {
            $payment->order->update([
                'payment_status' => $validated['payment_status'] === 'sukses' ? 'lunas' : 'menunggu_pembayaran',
            ]);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payment.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
