<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $perPage = 12; // 4 columns x 3 rows = 12 products per page

        $query = Product::with(['category', 'variants', 'testimonials.user'])
            ->where('status', 'aktif');

        // Search: matches product ID, name, category, variants, description
        if ($search = trim((string) $request->input('search', ''))) {
            $cleanId = ltrim($search, '#0');
            $rawId   = ltrim($search, '#');

            $query->where(function ($q) use ($search, $cleanId, $rawId) {
                if ($cleanId !== '' && is_numeric($cleanId)) {
                    $q->where('id', (int) $cleanId);
                } elseif ($rawId !== '' && is_numeric($rawId)) {
                    $q->where('id', (int) $rawId);
                }

                $q->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('category_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('color', 'like', "%{$search}%")
                         ->orWhere('size', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by color (through variants)
        if ($color = $request->input('color')) {
            $query->whereHas('variants', function ($q) use ($color) {
                $cLower = strtolower($color);
                if ($cLower === 'monokrom') {
                    $q->where(function ($sub) {
                        $sub->where('color', 'like', '%hitam%')
                            ->orWhere('color', 'like', '%black%')
                            ->orWhere('color', 'like', '%silver%')
                            ->orWhere('color', 'like', '%chrome%')
                            ->orWhere('color', 'like', '%putih%')
                            ->orWhere('color', 'like', '%white%');
                    });
                } elseif ($cLower === 'merah') {
                    $q->where(function ($sub) {
                        $sub->where('color', 'like', '%merah%')
                            ->orWhere('color', 'like', '%red%')
                            ->orWhere('color', 'like', '%pink%');
                    });
                } else {
                    $q->where('color', 'like', "%{$color}%");
                }
            });
        }

        // Filter by size (through variants)
        if ($size = $request->input('size')) {
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', $size);
            });
        }

        // Sort
        $sort = $request->input('sort', 'terlaris');
        switch ($sort) {
            case 'terlama':
                $query->oldest('id');
                break;
            case 'termurah':
                $query->orderBy('price', 'asc');
                break;
            case 'termahal':
                $query->orderBy('price', 'desc');
                break;
            case 'nama_az':
                $query->orderBy('product_name', 'asc');
                break;
            case 'nama_za':
                $query->orderBy('product_name', 'desc');
                break;
            case 'terbaru':
                $query->latest('id');
                break;
            default: // terlaris (default)
                $query->orderByDesc('order_count')->latest('id');
                break;
        }

        $products   = $query->paginate($perPage)->withQueryString();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('status', 'aktif');
        }])->get();

        // Get unique colors and sizes from product variants
        $colors = ProductVariant::whereNotNull('color')
            ->where('color', '!=', '')
            ->distinct()
            ->pluck('color')
            ->sort()
            ->values();

        $sizes = ProductVariant::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->sort()
            ->values();

        $totalProducts = Product::where('status', 'aktif')->count();

        // Hitung peringkat penjualan otomatis (Best Seller Top 3, Top 10)
        $rankedProductIds = Product::where('status', 'aktif')
            ->orderByDesc('order_count')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $rankMap = [];
        foreach ($rankedProductIds as $idx => $pId) {
            $rankMap[$pId] = $idx + 1; // 1, 2, 3, dst
        }

        // Cart count for current user (hanya jika logged in)
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        }

        return view('main.dashboard', compact(
            'products',
            'categories',
            'colors',
            'sizes',
            'totalProducts',
            'sort',
            'search',
            'cartCount',
            'rankMap'
        ));
    }

    public function cart(Request $request)
    {
        // Wajib user yang berada dalam session (login)
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses keranjang.');
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        $cartItems = Cart::with('product.category')->where('user_id', Auth::id())->get();

        if (view()->exists('main.cart')) {
            return view('main.cart', compact('cartCount', 'cartItems'));
        }

        return view('main.account.cart', compact('cartCount', 'cartItems'));
    }

    public function orderDetail(Request $request, $orderId = null)
    {
        // Wajib user login
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses detail pesanan.');
        }

        $paymentMethods = class_exists(\App\Models\PaymentMethod::class) ? \App\Models\PaymentMethod::all() : collect();
        $deliveryMethods = class_exists(\App\Models\DeliveryMethod::class) ? \App\Models\DeliveryMethod::all() : collect();
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        $user = Auth::user();

        if (view()->exists('main.order-progress.order-detail')) {
            return view('main.order-progress.order-detail', compact(
                'orderId',
                'paymentMethods',
                'deliveryMethods',
                'cartCount',
                'user'
            ));
        }

        return view('main.order-detail', compact('orderId', 'cartCount', 'user'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['category', 'variants', 'testimonials.user'])->findOrFail($id);

        // Testimonial khusus untuk produk ini
        $testimonials = $product->testimonials()
            ->with('user')
            ->where('is_store_testimonial', false)
            ->latest()
            ->get();

        // Related products in the same category
        $relatedProducts = Product::with(['category', 'variants', 'testimonials'])
            ->where('status', 'aktif')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::with(['category', 'variants', 'testimonials'])
                ->where('status', 'aktif')
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();
        }

        // Cart count
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        }

        return view('main.product-detail', compact('product', 'relatedProducts', 'cartCount', 'testimonials'));
    }

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            session()->put('url.intended', route('checkout'));
            return redirect()->route('login')->with('warning', 'Silahkan login terlebih dahulu untuk melanjutkan pemesanan.');
        }

        $user = Auth::user();
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        // Sample product fallback if cart empty
        $sampleProduct = Product::with('category')->first();

        return view('main.checkout', compact('cartCount', 'sampleProduct', 'user'));
    }

    public function processCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silahkan login terlebih dahulu untuk melanjutkan pemesanan.');
        }

        $method = $request->input('payment_method', 'qris');
        $address = $request->input('address', 'Banjarbaru, Kalimantan Selatan');
        $totalAmount = (float) $request->input('total_amount', 98000);
        $productId = $request->input('product_id');
        if (!$productId) {
            $firstProd = Product::first();
            $productId = $firstProd ? $firstProd->id : 1;
        }

        try {
            $order = \App\Models\Order::create([
                'user_id'        => Auth::id() ?? 1,
                'product_id'     => $productId,
                'quantity'       => (int) $request->input('quantity', 1),
                'subtotal'       => $totalAmount,
                'order_status'   => 'Pending',
                'payment_status' => 'belum_dibayar',
                'address'        => $address,
                'order_at'       => now(),
            ]);

            \App\Models\Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $method,
                'amount'         => $totalAmount,
                'payment_status' => 'pending',
            ]);

            $orderId = $order->id;
        } catch (\Throwable $e) {
            $orderId = rand(1001, 9999);
        }

        session()->put('current_order', [
            'id'             => $orderId,
            'amount'         => $totalAmount,
            'payment_method' => $method,
            'address'        => $address,
            'name'           => $request->input('customer_name', Auth::user()?->name ?? 'Customer Maybeads'),
            'phone'          => $request->input('customer_phone', '081234567890'),
            'date'           => now()->format('d M Y, H:i'),
        ]);

        if ($method === 'transfer') {
            return redirect()->route('payment.transfer', ['order_id' => $orderId]);
        }

        return redirect()->route('payment.qris', ['order_id' => $orderId]);
    }

    public function paymentQris(Request $request, $orderId = null)
    {
        $dbOrder = null;
        if ($orderId && is_numeric($orderId)) {
            $dbOrder = Order::with('user', 'product')->find($orderId);
        }

        $sessionOrder = session('current_order', []);

        $order = [
            'id'             => $dbOrder ? $dbOrder->id : ($orderId ?? ($sessionOrder['id'] ?? 'ORD-' . rand(1000, 9999))),
            'amount'         => $dbOrder ? (float)$dbOrder->subtotal : (float)($sessionOrder['amount'] ?? 98000),
            'payment_method' => 'qris',
            'date'           => $dbOrder && $dbOrder->created_at ? $dbOrder->created_at->format('d M Y, H:i') : ($sessionOrder['date'] ?? now()->format('d M Y, H:i')),
            'status'         => $dbOrder ? $dbOrder->payment_status : 'belum_dibayar',
        ];

        // Batas waktu pembayaran 10 menit (600 detik) terhitung di sistem sejak order_at
        if ($dbOrder) {
            if (!$dbOrder->order_at) {
                $dbOrder->update(['order_at' => now()]);
            }
            $startTime = \Carbon\Carbon::parse($dbOrder->order_at);
        } else {
            $sessionTimerKey = 'order_start_time_' . $order['id'];
            if (!session()->has($sessionTimerKey)) {
                session()->put($sessionTimerKey, now()->toDateTimeString());
            }
            $startTime = \Carbon\Carbon::parse(session($sessionTimerKey));
        }

        $expiresAt = $startTime->copy()->addMinutes(10);
        $now = now();

        if ($now->greaterThanOrEqualTo($expiresAt)) {
            $remainingSeconds = 0;
            $isExpired = true;
        } else {
            $remainingSeconds = (int) $now->diffInSeconds($expiresAt, false);
            $isExpired = false;
        }

        if ($remainingSeconds <= 0 && !in_array(strtolower($order['status']), ['sudah_dibayar', 'lunas', 'paid', 'sukses'])) {
            $isExpired = true;
            if ($dbOrder) {
                $dbOrder->update([
                    'order_status'   => 'Dibatalkan',
                    'payment_status' => 'dibatalkan',
                ]);
                Payment::where('order_id', $dbOrder->id)->update([
                    'payment_status' => 'dibatalkan',
                ]);
            }
        }

        // Auto-generate QRIS via Midtrans API (.env)
        $customer = [
            'name'  => $dbOrder?->user?->name ?? (Auth::user()?->name ?? 'Customer Maybeads'),
            'email' => $dbOrder?->user?->email ?? (Auth::user()?->email ?? 'customer@maybeads.com'),
            'phone' => $dbOrder?->user?->phone ?? '081234567890',
        ];

        $qrisData = \App\Services\MidtransService::generateQris($order['id'], $order['amount'], $customer);

        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        if (view()->exists('main.payment.QRIS.payment-qris')) {
            return view('main.payment.QRIS.payment-qris', compact('order', 'cartCount', 'qrisData', 'remainingSeconds', 'isExpired'));
        }

        return view('main.payment-qris', compact('order', 'cartCount', 'qrisData', 'remainingSeconds', 'isExpired'));
    }

    public function refreshQris(Request $request, $orderId = null)
    {
        $dbOrder = null;
        if ($orderId && is_numeric($orderId)) {
            $dbOrder = Order::find($orderId);
        }

        $sessionOrder = session('current_order', []);
        $id     = $dbOrder ? $dbOrder->id : ($orderId ?? ($sessionOrder['id'] ?? 'ORD-' . rand(1000, 9999)));
        $amount = $dbOrder ? (float)$dbOrder->subtotal : (float)($sessionOrder['amount'] ?? 98000);

        $customer = [
            'name'  => $dbOrder?->user?->name ?? (Auth::user()?->name ?? 'Customer Maybeads'),
            'email' => $dbOrder?->user?->email ?? (Auth::user()?->email ?? 'customer@maybeads.com'),
            'phone' => $dbOrder?->user?->phone ?? '081234567890',
        ];

        // Hitung sisa waktu pesanan saat ini (refresh hanya berlaku untuk QRIS itu sendiri, tidak mengulang timer pesanan)
        if ($dbOrder && $dbOrder->order_at) {
            $startTime = \Carbon\Carbon::parse($dbOrder->order_at);
        } else {
            $sessionTimerKey = 'order_start_time_' . $id;
            $startTime = session()->has($sessionTimerKey) ? \Carbon\Carbon::parse(session($sessionTimerKey)) : now();
        }
        $expiresAt = $startTime->copy()->addMinutes(10);
        $remainingSeconds = max(0, (int) now()->diffInSeconds($expiresAt, false));

        // Hanya refresh data QRIS melalui API Midtrans
        $qrisData = \App\Services\MidtransService::generateQris($id, $amount, $customer, true);

        return response()->json([
            'success'           => true,
            'qr_url'            => $qrisData['qr_url'],
            'simulator_url'     => $qrisData['simulator_url'] ?? $qrisData['qr_url'],
            'image_url'         => $qrisData['image_url'] ?? $qrisData['qr_url'],
            'qr_string'         => $qrisData['qr_string'],
            'remaining_seconds' => $remainingSeconds,
            'message'           => 'QRIS berhasil diperbarui!',
        ]);
    }

    public function cancelQris(Request $request, $orderId = null)
    {
        if ($orderId && is_numeric($orderId)) {
            Order::where('id', $orderId)->update([
                'order_status'   => 'Dibatalkan',
                'payment_status' => 'dibatalkan',
            ]);
            Payment::where('order_id', $orderId)->update([
                'payment_status' => 'dibatalkan',
            ]);
        }
        session()->forget('order_start_time_' . $orderId);
        session()->forget('midtrans_qris_' . $orderId);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan telah otomatis dibatalkan karena batas waktu pembayaran habis.',
        ]);
    }

    public function downloadQris(Request $request, $orderId = null)
    {
        $url = $request->query('url');

        if (!$url) {
            $customer = [
                'name'  => Auth::user()?->name ?? 'Customer Maybeads',
                'email' => Auth::user()?->email ?? 'customer@maybeads.com',
                'phone' => '081234567890',
            ];
            $qrisData = \App\Services\MidtransService::generateQris($orderId ?? '1', 98000, $customer);
            $url = $qrisData['qr_url'];
        }

        $cleanOrderId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($orderId ?? 'ORD'));
        $filename = "QRIS-Maybeads-{$cleanOrderId}.png";

        // Jika URL adalah path lokal di server
        if (str_starts_with($url, '/') || str_contains($url, request()->getHost())) {
            $parsedPath = parse_url($url, PHP_URL_PATH);
            $localFile = public_path(ltrim($parsedPath, '/'));
            if (file_exists($localFile)) {
                return response()->download($localFile, $filename, [
                    'Content-Type'  => 'image/png',
                    'Cache-Control' => 'no-cache, must-revalidate',
                ]);
            }
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(6)->get($url);
            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?: 'image/png';
                return response($response->body(), 200, [
                    'Content-Type'        => $contentType,
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    'Cache-Control'       => 'no-cache, no-store, must-revalidate',
                ]);
            }
        } catch (\Throwable $e) {}

        return redirect()->away($url);
    }

    public function paymentTransfer($orderId = null)
    {
        $order = session('current_order', [
            'id'             => $orderId ?? 'ORD-' . rand(1000, 9999),
            'amount'         => 98000,
            'payment_method' => 'transfer',
            'date'           => now()->format('d M Y, H:i'),
        ]);

        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        if (view()->exists('main.payment.QRIS.payment-transfer')) {
            return view('main.payment.QRIS.payment-transfer', compact('order', 'cartCount'));
        }

        return view('main.payment-transfer', compact('order', 'cartCount'));
    }

    public function paymentSuccess($orderId = null)
    {
        $order = session('current_order', [
            'id'             => $orderId ?? 'ORD-' . rand(1000, 9999),
            'amount'         => 98000,
            'payment_method' => 'qris',
            'address'        => 'Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru',
            'name'           => Auth::user()?->name ?? 'Customer Maybeads',
            'phone'          => '081234567890',
            'date'           => now()->format('d M Y, H:i'),
        ]);

        // Update status in DB if exists: masukkan ke status orderan Pending sebelum diterima oleh sang pemilik toko
        try {
            if ($orderId && is_numeric($orderId)) {
                \App\Models\Order::where('id', $orderId)->update([
                    'payment_status' => 'sudah_dibayar',
                    'order_status'   => 'Pending',
                ]);
                \App\Models\Payment::where('order_id', $orderId)->update([
                    'payment_status' => 'sukses',
                    'paid_at'        => now(),
                ]);
            }
        } catch (\Throwable $e) {}

        $cartCount = 0;

        if (view()->exists('main.payment.QRIS.payment-success')) {
            return view('main.payment.QRIS.payment-success', compact('order', 'cartCount'));
        }

        return view('main.payment-success', compact('order', 'cartCount'));
    }

    public function myOrders()
    {
        $userId = Auth::id() ?? 1;
        $orders = \App\Models\Order::with(['product', 'payment'])
            ->where('user_id', $userId)
            ->latest('id')
            ->get();

        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        return view('main.orders', compact('orders', 'cartCount'));
    }
};