<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $perPage = 12; // 4 columns x 3 rows = 12 products per page

        $query = Product::with(['category', 'variants'])
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
        $sort = $request->input('sort', 'terbaru');
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
            default: // terbaru
                $query->latest('id');
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

        // Cart count for current user
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cartCount = count(session()->get('cart', []));
        }

        return view('main.dashboard', compact(
            'products',
            'categories',
            'colors',
            'sizes',
            'totalProducts',
            'sort',
            'search',
            'cartCount'
        ));
    }

    public function cart(Request $request)
    {
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cartCount = count(session()->get('cart', []));
        }

        return view('main.cart', compact('cartCount'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['category', 'variants'])->find($id);
        if (!$product) {
            $product = Product::with(['category', 'variants'])->firstOrFail();
        }

        $relatedProducts = Product::with(['category'])
            ->where('id', '!=', $product->id)
            ->where('status', 'aktif')
            ->take(4)
            ->get();

        $cartCount = Auth::check()
            ? Cart::where('user_id', Auth::id())->sum('quantity')
            : count(session()->get('cart', []));

        return view('main.product-detail', compact('product', 'relatedProducts', 'cartCount'));
    }

    public function checkout(Request $request)
    {
        $cartCount = Auth::check()
            ? Cart::where('user_id', Auth::id())->sum('quantity')
            : count(session()->get('cart', []));

        // Sample product fallback if cart empty
        $sampleProduct = Product::with('category')->first();

        return view('main.checkout', compact('cartCount', 'sampleProduct'));
    }

    public function processCheckout(Request $request)
    {
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
                'order_status'   => 'diproses',
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

    public function paymentQris($orderId = null)
    {
        $order = session('current_order', [
            'id'             => $orderId ?? 'ORD-' . rand(1000, 9999),
            'amount'         => 98000,
            'payment_method' => 'qris',
            'date'           => now()->format('d M Y, H:i'),
        ]);

        $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        return view('main.payment-qris', compact('order', 'cartCount'));
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

        // Update status in DB if exists
        try {
            if ($orderId && is_numeric($orderId)) {
                \App\Models\Order::where('id', $orderId)->update([
                    'payment_status' => 'sudah_dibayar',
                    'order_status'   => 'diproses'
                ]);
                \App\Models\Payment::where('order_id', $orderId)->update([
                    'payment_status' => 'berhasil',
                    'paid_at'        => now()
                ]);
            }
        } catch (\Throwable $e) {}

        $cartCount = 0;

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
}
