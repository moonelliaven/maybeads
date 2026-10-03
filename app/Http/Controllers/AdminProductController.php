<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $categoryFilter = $request->query('category', 'Semua');
        $perPageParam = $request->query('per_page', 8);

        $query = Product::with('category');

        if (!empty($categoryFilter) && $categoryFilter !== 'Semua') {
            $query->whereHas('category', function ($q) use ($categoryFilter) {
                $q->where('category_name', $categoryFilter);
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $term = '%' . strtolower($search) . '%';
                $q->whereRaw('LOWER(product_name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$term]);
            });
        }

        $totalProducts = Product::count();
        $filteredCount = (clone $query)->count();

        if ($perPageParam === 'all' || $perPageParam == -1) {
            $perPage = max($filteredCount, 1);
        } else {
            $perPage = is_numeric($perPageParam) ? (int)$perPageParam : 8;
        }

        $products = $query->orderBy('id', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::orderBy('id', 'asc')->get();

        return view('admin.produk.index', compact(
            'products',
            'categories',
            'totalProducts',
            'filteredCount',
            'categoryFilter',
            'search',
            'perPageParam'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('category_name')->get();
        return view('admin.produk.create', compact('categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.produk.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('category_name')->get();
        return view('admin.produk.edit', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'product_name' => ['required', 'string', 'max:100', 'unique:products,product_name'],
            'price'        => ['required', 'string', 'max:100'],
            'stock'        => ['required', 'integer', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable'],
            'variants'     => ['nullable', 'string'],
            'status'       => ['nullable'],
        ]);

        // Handle Image
        $imageName = 'bead-default.jpg';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = 'prd-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $imageName);
        } elseif (!empty($request->input('image'))) {
            $imageName = substr($request->input('image'), 0, 20);
        }

        $status = $request->has('status') ? (bool)$request->input('status') : true;

        // Handle additional images if uploaded
        $additional = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $idx => $extraFile) {
                if ($idx < 5) {
                    $extraName = 'prd-extra-' . time() . '-' . $idx . '.' . $extraFile->getClientOriginalExtension();
                    $extraFile->move(public_path('images/products'), $extraName);
                    $additional[] = $extraName;
                }
            }
        }

        Product::create([
            'category_id'        => $validated['category_id'],
            'product_name'       => $validated['product_name'],
            'price'              => $validated['price'],
            'stock'              => $validated['stock'],
            'description'        => $validated['description'] ?? null,
            'image'              => $imageName,
            'variants'           => $request->input('variants', 'Hitam, Silver, Pink'),
            'status'             => $status,
            'additional_images'  => !empty($additional) ? json_encode($additional) : null,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'product_name' => ['required', 'string', 'max:100', 'unique:products,product_name,' . $product->id],
            'price'        => ['required', 'string', 'max:100'],
            'stock'        => ['required', 'integer', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable'],
            'variants'     => ['nullable', 'string'],
            'status'       => ['nullable'],
        ]);

        $imageName = $product->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = 'prd-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $imageName);
        } elseif (!empty($request->input('image'))) {
            $imageName = substr($request->input('image'), 0, 20);
        }

        $status = $request->has('status') ? (bool)$request->input('status') : false;

        $additional = !empty($product->additional_images) ? json_decode($product->additional_images, true) : [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $idx => $extraFile) {
                if (count($additional) < 5) {
                    $extraName = 'prd-extra-' . time() . '-' . count($additional) . '.' . $extraFile->getClientOriginalExtension();
                    $extraFile->move(public_path('images/products'), $extraName);
                    $additional[] = $extraName;
                }
            }
        }

        $product->update([
            'category_id'        => $validated['category_id'],
            'product_name'       => $validated['product_name'],
            'price'              => $validated['price'],
            'stock'              => $validated['stock'],
            'description'        => $validated['description'] ?? null,
            'image'              => $imageName,
            'variants'           => $request->input('variants', $product->variants),
            'status'             => $status,
            'additional_images'  => !empty($additional) ? json_encode($additional) : null,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil dihapus.');
    }
}
