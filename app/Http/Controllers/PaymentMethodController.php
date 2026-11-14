<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Menampilkan daftar metode pembayaran (Management Khusus Metode dari Admin)
     */
    public function index(Request $request)
    {
        $activeTab = strtolower($request->query('tab', 'semua'));
        $search = trim($request->query('q', ''));
        $statusFilter = strtolower($request->query('status', 'semua'));

        $query = PaymentMethod::with('payments')->orderBy('id', 'asc');

        // Filter kategori / tab
        if ($activeTab !== 'semua') {
            if ($activeTab === 'bank') {
                $query->where('type', 'bank');
            } elseif ($activeTab === 'auto-link' || $activeTab === 'autolink') {
                $query->where('type', 'auto-link');
            } elseif ($activeTab === 'qris') {
                $query->where('type', 'qris');
            } elseif ($activeTab === 'e-wallet' || $activeTab === 'ewallet') {
                $query->where('type', 'e-wallet');
            }
        }

        // Filter status
        if ($statusFilter !== 'semua') {
            if ($statusFilter === 'active' || $statusFilter === 'aktif') {
                $query->where('status', 'active');
            } elseif ($statusFilter === 'non-active' || $statusFilter === 'nonaktif') {
                $query->where('status', 'non-active');
            }
        }

        // Pencarian nama, kode, no rekening, nama pemilik
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $methods = $query->get();

        // Hitung statistik akumulasi harga per wallet maupun total transaksi
        $allMethods = PaymentMethod::with('payments')->get();
        $allSuccessPayments = Payment::whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'Success'])->get();

        $totalRevenue = $allSuccessPayments->sum('amount');
        $totalTransactionsCount = $allSuccessPayments->count();

        // Akumulasi per kategori
        $bankRevenue = $allMethods->where('type', 'bank')->sum(fn($m) => $m->total_accumulated);
        $bankTrxCount = $allMethods->where('type', 'bank')->sum(fn($m) => $m->transactions_count);

        $autoLinkRevenue = $allMethods->where('type', 'auto-link')->sum(fn($m) => $m->total_accumulated);
        $autoLinkTrxCount = $allMethods->where('type', 'auto-link')->sum(fn($m) => $m->transactions_count);

        $qrisRevenue = $allMethods->where('type', 'qris')->sum(fn($m) => $m->total_accumulated);
        $qrisTrxCount = $allMethods->where('type', 'qris')->sum(fn($m) => $m->transactions_count);

        $ewalletRevenue = $allMethods->where('type', 'e-wallet')->sum(fn($m) => $m->total_accumulated);
        $ewalletTrxCount = $allMethods->where('type', 'e-wallet')->sum(fn($m) => $m->transactions_count);

        $activeMethodsCount = $allMethods->where('status', 'active')->count();
        $avgAdminFee = $allMethods->count() > 0 ? $allMethods->avg('price') : 0;

        $stats = [
            'total_revenue'         => $totalRevenue,
            'total_transactions'    => $totalTransactionsCount,
            'bank_revenue'          => $bankRevenue,
            'bank_trx_count'        => $bankTrxCount,
            'autolink_revenue'      => $autoLinkRevenue,
            'autolink_trx_count'    => $autoLinkTrxCount,
            'qris_revenue'          => $qrisRevenue,
            'qris_trx_count'        => $qrisTrxCount,
            'ewallet_revenue'       => $ewalletRevenue,
            'ewallet_trx_count'     => $ewalletTrxCount,
            'active_methods_count'  => $activeMethodsCount,
            'total_methods_count'   => $allMethods->count(),
            'avg_admin_fee'         => $avgAdminFee,
        ];

        return view('admin.payment-method.index', compact('methods', 'stats', 'activeTab', 'search', 'statusFilter'));
    }

    /**
     * Tambah metode pembayaran baru oleh admin (Bank, Auto-Link, QRIS, E-Wallet)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:payment_methods,code',
            'type'           => 'required|string|in:bank,auto-link,qris,e-wallet',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:active,non-active',
            'description'    => 'nullable|string',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-method.index')
            ->with('success', "Metode pembayaran '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Update metode pembayaran (ubah biaya admin, nama akun, status, dll.)
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'type'           => 'required|string|in:bank,auto-link,qris,e-wallet',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:active,non-active',
            'description'    => 'nullable|string',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-method.index')
            ->with('success', "Metode pembayaran '{$paymentMethod->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif / non-aktif
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $newStatus = $paymentMethod->status === 'active' ? 'non-active' : 'active';
        $paymentMethod->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status metode '{$paymentMethod->name}' berhasil {$statusText}.");
    }

    /**
     * Hapus metode pembayaran
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $name = $paymentMethod->name;
        $paymentMethod->delete();

        return redirect()->route('admin.payment-method.index')
            ->with('success', "Metode pembayaran '{$name}' berhasil dihapus.");
    }
}

