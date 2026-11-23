<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja pengguna
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $cartCount = 0;
        $cartItems = collect();

        if ($userId) {
            $cartItems = Cart::with(['product.category'])
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();
            $cartCount = (int) $cartItems->sum('quantity');
        }

        // Format data untuk dikirim ke view dan JS
        $formattedItems = $cartItems->map(function ($c) {
            $prod = $c->product;
            $rawPrice = $prod ? ((float)$prod->price < 1000 ? (float)$prod->price * 1000 : (float)$prod->price) : 25000;
            $origPrice = $rawPrice * 1.15;

            return [
                'id'            => $c->id,
                'productId'     => $c->product_id,
                'name'          => $prod ? $prod->product_name : 'Produk Maybeads',
                'category'      => $prod && $prod->category ? $prod->category->category_name : 'Aksesoris',
                'price'         => 'Rp ' . number_format($rawPrice, 0, ',', '.'),
                'rawPrice'      => $rawPrice,
                'originalPrice' => 'Rp ' . number_format($origPrice, 0, ',', '.'),
                'qty'           => (int) $c->quantity,
                'stock'         => $prod ? (int)$prod->stock : 99,
                'image'         => $prod ? ($prod->image_url ?? $prod->image ?? asset('images/landing/prod-hairclip.jpg')) : asset('images/landing/prod-hairclip.jpg'),
                'checked'       => true,
            ];
        });

        return view('main.account.cart', [
            'cartCount'     => $cartCount,
            'cartItems'     => $cartItems,
            'cartItemsJson' => $formattedItems->values()->all(),
        ]);
    }

    /**
     * Tambah produk ke keranjang belanja pengguna (Sync ke Database)
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.',
                'need_login' => true,
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $userId = Auth::id();
        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Cek stok
        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok produk ini sedang habis.'], 422);
        }

        $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cart) {
            $newQty = $cart->quantity + $quantity;
            if ($newQty > $product->stock) {
                $newQty = $product->stock;
            }
            $cart->update(['quantity' => $newQty]);
        } else {
            $cart = Cart::create([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => min($quantity, $product->stock),
                'added_at'   => now(),
            ]);
        }

        $totalCount = (int) Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'success'   => true,
            'message'   => 'Produk berhasil ditambahkan ke keranjang!',
            'cartCount' => $totalCount,
            'cartItem'  => [
                'id'        => $cart->id,
                'productId' => $product->id,
                'name'      => $product->product_name,
                'qty'       => $cart->quantity,
            ]
        ]);
    }

    /**
     * Update kuantitas item keranjang di database
     */
    public function updateQty(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update(['quantity' => (int) $validated['quantity']]);

        $totalCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'cartCount' => $totalCount,
        ]);
    }

    /**
     * Hapus 1 produk spesifik dari keranjang di database
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        $totalCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'cartCount' => $totalCount,
            'message'   => 'Item berhasil dihapus dari keranjang.',
        ]);
    }

    /**
     * Hapus semua atau multiple produk dari keranjang di database
     */
    public function clear(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $ids = $request->input('ids');
        if (is_array($ids) && count($ids) > 0) {
            Cart::where('user_id', Auth::id())->whereIn('id', $ids)->delete();
        } else {
            Cart::where('user_id', Auth::id())->delete();
        }

        $totalCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success'   => true,
            'cartCount' => $totalCount,
            'message'   => 'Keranjang berhasil dibersihkan.',
        ]);
    }

    /**
     * API untuk mendapatkan data item keranjang terbaru
     */
    public function items(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['cartCount' => 0, 'items' => []]);
        }

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $totalCount = (int) $cartItems->sum('quantity');

        return response()->json([
            'cartCount' => $totalCount,
            'items'     => $cartItems,
        ]);
    }
}

