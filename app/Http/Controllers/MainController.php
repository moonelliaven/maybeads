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
        $perPage = 20; // 5 columns x 4 rows max as requested (5x4)

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
                $q->where('color', $color);
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
}
