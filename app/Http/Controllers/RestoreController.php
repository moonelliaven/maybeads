<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    /**
     * Tampilkan data sampah (produk & kategori yang dihapus sementara)
     */
    public function index(Request $request)
    {
        // 1. Bersihkan item yang sudah melebihi 30 hari secara otomatis
        $expiredProducts = Product::onlyTrashed()->where('deleted_at', '<=', now()->subDays(30))->get();
        foreach ($expiredProducts as $ep) {
            $ep->forceDelete();
        }

        $expiredCategories = Category::onlyTrashed()->where('deleted_at', '<=', now()->subDays(30))->get();
        foreach ($expiredCategories as $ec) {
            $ec->forceDelete();
        }

        // 2. Ambil data dalam masa tenggang 30 hari
        $trashedProducts = Product::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc')->get()->map(function ($p) {
            $daysLeft = max(0, 30 - (int) $p->deleted_at->diffInDays(now()));
            return [
                'id'            => $p->id,
                'name'          => $p->product_name,
                'category_name' => $p->category?->category_name ?? 'Tanpa Kategori',
                'image'         => $p->image,
                'price'         => (float) $p->price,
                'stock'         => (int) $p->stock,
                'deleted_at'    => $p->deleted_at->format('d M Y, H:i'),
                'days_left'     => $daysLeft,
            ];
        });

        $trashedCategories = Category::onlyTrashed()->orderBy('deleted_at', 'desc')->get()->map(function ($c) {
            $daysLeft = max(0, 30 - (int) $c->deleted_at->diffInDays(now()));
            return [
                'id'            => $c->id,
                'name'          => $c->category_name,
                'image'         => $c->image,
                'deleted_at'    => $c->deleted_at->format('d M Y, H:i'),
                'days_left'     => $daysLeft,
            ];
        });

        $activeTab = $request->query('tab', 'product');

        return view('admin.restore.index', compact('trashedProducts', 'trashedCategories', 'activeTab'));
    }

    /**
     * Pulihkan produk yang dihapus
     */
    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.restore.index', ['tab' => 'product'])
            ->with('success', "Produk '{$product->product_name}' berhasil dipulihkan.");
    }

    /**
     * Hapus permanen produk
     */
    public function forceDeleteProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $name = $product->product_name;
        $product->forceDelete();

        return redirect()->route('admin.restore.index', ['tab' => 'product'])
            ->with('success', "Produk '{$name}' telah dihapus secara permanen.");
    }

    /**
     * Pulihkan kategori yang dihapus
     */
    public function restoreCategory($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.restore.index', ['tab' => 'category'])
            ->with('success', "Kategori '{$category->category_name}' berhasil dipulihkan.");
    }

    /**
     * Hapus permanen kategori
     */
    public function forceDeleteCategory($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $name = $category->category_name;
        $category->forceDelete();

        return redirect()->route('admin.restore.index', ['tab' => 'category'])
            ->with('success', "Kategori '{$name}' telah dihapus secara permanen.");
    }
}

