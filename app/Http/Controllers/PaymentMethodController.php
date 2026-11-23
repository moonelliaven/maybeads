<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Menampilkan daftar metode pembayaran (Management Khusus Metode dari Admin)
     */
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $statusFilter = strtolower($request->query('status', 'semua'));
        $typeFilter = strtolower($request->query('type', 'semua'));

        // Filter Bulan & Tahun untuk pemasukan
        $selectedMonth = (int) $request->input('month', date('n'));
        $selectedYear  = (int) $request->input('year', date('Y'));
        $sortBy        = $request->input('sort', 'default');

        $query = PaymentMethod::with('payments');

        // Filter tipe
        if ($typeFilter !== 'semua') {
            $query->where('type', $typeFilter);
        }

        // Filter status
        if ($statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        // Search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        $allMethods = $query->get();

        // Hitung pemasukan per metode untuk bulan terpilih dan akumulasi keseluruhan
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        foreach ($allMethods as $method) {
            $methodPayments = $method->payments->filter(function ($p) {
                return in_array(strtolower($p->payment_status), ['sukses', 'paid', 'lunas', 'berhasil']);
            });

            $method->total_revenue = (float) $methodPayments->sum('amount');
            $method->month_revenue = (float) $methodPayments->filter(function ($p) use ($startDate, $endDate) {
                $date = $p->paid_at ?? $p->created_at;
                return $date && $date >= $startDate && $date <= $endDate;
            })->sum('amount');
        }

        // Sortir
        if ($sortBy === 'month_income_desc') {
            $methods = $allMethods->sortByDesc('month_revenue')->values();
        } elseif ($sortBy === 'total_income_desc') {
            $methods = $allMethods->sortByDesc('total_revenue')->values();
        } elseif ($sortBy === 'fee_desc') {
            $methods = $allMethods->sortByDesc('price')->values();
        } elseif ($sortBy === 'name_asc') {
            $methods = $allMethods->sortBy('name')->values();
        } else {
            $methods = $allMethods->sortBy('id')->values();
        }

        // Total Akumulasi Keseluruhan
        $allSuccessPayments = Payment::whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'lunas', 'berhasil'])->get();
        $totalRevenueAll = (float) $allSuccessPayments->sum('amount');

        // Pemasukan Bulan Terpilih Keseluruhan
        $monthRevenueAll = (float) $allSuccessPayments->filter(function ($p) use ($startDate, $endDate) {
            $date = $p->paid_at ?? $p->created_at;
            return $date && $date >= $startDate && $date <= $endDate;
        })->sum('amount');

        $activeMethodsCount = PaymentMethod::where('status', 'active')->count();
        $totalMethodsCount  = PaymentMethod::count();

        $totalMethods = $methods->count();
        $isAllMode = $request->input('show_all') === 'true';
        $perPage = $isAllMode ? max($totalMethods, 1) : 10;
        $currentPage = (int) $request->input('page', 1);
        $paginatedItems = $isAllMode ? $methods : $methods->forPage($currentPage, $perPage)->values();
        $methods = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $totalMethods,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $stats = [
            'total_revenue_all' => $totalRevenueAll,
            'month_revenue_all' => $monthRevenueAll,
            'selected_month'    => $selectedMonth,
            'selected_year'     => $selectedYear,
            'active_count'      => $activeMethodsCount,
            'total_count'       => $totalMethodsCount,
            'total_methods'     => $totalMethods,
        ];

        return view('admin.payment-method.index', compact(
            'methods',
            'stats',
            'search',
            'statusFilter',
            'typeFilter',
            'selectedMonth',
            'selectedYear',
            'sortBy'
        ));
    }

    /**
     * Halaman Khusus Tambah Metode Pembayaran (Bukan pop up)
     */
    public function create()
    {
        return view('admin.payment-method.create');
    }

    /**
     * Tambah metode pembayaran baru oleh admin
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
     * Halaman Khusus Detail Metode Pembayaran (Bukan pop up)
     */
    public function show(PaymentMethod $paymentMethod)
    {
        $paymentMethod->load('payments.order.user');

        $allPayments = $paymentMethod->payments->filter(function ($p) {
            return in_array(strtolower($p->payment_status), ['sukses', 'paid', 'lunas', 'berhasil']);
        });

        $totalRevenue = (float) $allPayments->sum('amount');
        $transactionCount = $allPayments->count();

        return view('admin.payment-method.show', compact('paymentMethod', 'totalRevenue', 'transactionCount'));
    }

    /**
     * Halaman Khusus Edit Metode Pembayaran
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-method.edit', compact('paymentMethod'));
    }

    /**
     * Update metode pembayaran
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
    public function toggleStatus(Request $request, PaymentMethod $paymentMethod)
    {
        $newStatus = $paymentMethod->status === 'active' ? 'non-active' : 'active';
        $paymentMethod->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'status'  => $newStatus,
                'message' => "Metode '{$paymentMethod->name}' berhasil {$statusText}."
            ]);
        }

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
