<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get()->map(function ($p) {
            return [
                'id'            => $p->id,
                'product_name'  => $p->product_name,
                'category_id'   => $p->category_id,
                'category_name' => $p->category?->category_name ?? 'Tanpa Kategori',
                'image'         => $p->image,
                'price'         => $p->price,
                'stock'         => $p->stock,
                'description'   => $p->description,
            ];
        });

        $categories = Category::all(['id', 'category_name']);

        return view('admin.product.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Helper: simpan gambar ke storage/app/public/product
     */
    private function storeImage($file): string
    {
        $dir = storage_path('app/public/product');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        $name = uniqid() . '.' . $file->extension();
        $file->move($dir, $name);
        return $name;
    }

    /**
     * Helper: hapus gambar dari storage/product atau public/product
     */
    private function deleteImage(?string $filename): void
    {
        if (!$filename) return;
        $paths = [
            storage_path('app/public/product/' . $filename),
            public_path('product/' . $filename),
        ];
        foreach ($paths as $p) {
            if (file_exists($p)) { unlink($p); return; }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'     => 'required|string|max:100',
            'category_id'      => 'required',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'secondary_images' => 'nullable|array|max:5',
            'secondary_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'      => 'nullable|string',
            'price'            => 'required|string|max:100',
            'stock'            => 'required|integer|min:0',
            'variants'         => 'nullable|array',
            'variants.*'       => 'string|max:100',
            'status'           => 'nullable',
        ]);

        // Handle custom category
        $categoryId = $request->input('category_id');
        if ($categoryId === 'custom') {
            $customName = $request->input('custom_category_name');
            if ($customName) {
                $cat = Category::firstOrCreate(['category_name' => trim($customName)]);
                $categoryId = $cat->id;
            } else {
                return back()->withErrors(['category_id' => 'Nama kategori custom wajib diisi.'])->withInput();
            }
        }
        $validated['category_id'] = $categoryId;

        // Secondary categories (JSON array of IDs, include custom ones)
        $secCats = [];
        $secCatIds = $request->input('secondary_category_ids', []);
        $secCatCustoms = $request->input('secondary_category_customs', []);
        foreach ($secCatIds as $i => $scId) {
            if ($scId === 'custom' && !empty($secCatCustoms[$i])) {
                $c = Category::firstOrCreate(['category_name' => trim($secCatCustoms[$i])]);
                $secCats[] = $c->id;
            } elseif ($scId && $scId !== 'custom') {
                $secCats[] = (int) $scId;
            }
        }
        $validated['secondary_categories'] = !empty($secCats) ? $secCats : null;

        // Image utama
        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        // Secondary images
        $secImages = [];
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $f) {
                $secImages[] = $this->storeImage($f);
            }
        }
        $validated['secondary_images'] = !empty($secImages) ? $secImages : null;

        // Variants (filter empty)
        $variants = array_values(array_filter($request->input('variants', []), fn($v) => trim($v) !== ''));
        $validated['variants'] = !empty($variants) ? $variants : null;

        // Status
        $validated['status'] = $request->has('status') ? true : false;

        Product::create($validated);

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name'     => 'required|string|max:100',
            'category_id'      => 'required',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'secondary_images' => 'nullable|array|max:5',
            'secondary_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'      => 'nullable|string',
            'price'            => 'required|string|max:100',
            'stock'            => 'required|integer|min:0',
            'variants'         => 'nullable|array',
            'variants.*'       => 'string|max:100',
            'status'           => 'nullable',
        ]);

        // Handle custom category
        $categoryId = $request->input('category_id');
        if ($categoryId === 'custom') {
            $customName = $request->input('custom_category_name');
            if ($customName) {
                $cat = Category::firstOrCreate(['category_name' => trim($customName)]);
                $categoryId = $cat->id;
            } else {
                return back()->withErrors(['category_id' => 'Nama kategori custom wajib diisi.'])->withInput();
            }
        }
        $validated['category_id'] = $categoryId;

        // Secondary categories
        $secCats = [];
        $secCatIds = $request->input('secondary_category_ids', []);
        $secCatCustoms = $request->input('secondary_category_customs', []);
        foreach ($secCatIds as $i => $scId) {
            if ($scId === 'custom' && !empty($secCatCustoms[$i])) {
                $c = Category::firstOrCreate(['category_name' => trim($secCatCustoms[$i])]);
                $secCats[] = $c->id;
            } elseif ($scId && $scId !== 'custom') {
                $secCats[] = (int) $scId;
            }
        }
        $validated['secondary_categories'] = !empty($secCats) ? $secCats : null;

        // Image utama
        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        // Secondary images (tambahkan baru, gabung dengan yg lama jika ada)
        $existingSec = $product->secondary_images ?? [];
        // Hapus yang diminta untuk dihapus
        $removeSecIdx = $request->input('remove_secondary_images', []);
        foreach ($removeSecIdx as $idx) {
            if (isset($existingSec[$idx])) {
                $this->deleteImage($existingSec[$idx]);
                unset($existingSec[$idx]);
            }
        }
        $existingSec = array_values($existingSec);
        // Tambahkan baru
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $f) {
                if (count($existingSec) < 5) {
                    $existingSec[] = $this->storeImage($f);
                }
            }
        }
        $validated['secondary_images'] = !empty($existingSec) ? $existingSec : null;

        // Variants
        $variants = array_values(array_filter($request->input('variants', []), fn($v) => trim($v) !== ''));
        $validated['variants'] = !empty($variants) ? $variants : null;

        // Status
        $validated['status'] = $request->has('status') ? true : false;

        $product->update($validated);

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        if ($product->secondary_images) {
            foreach ($product->secondary_images as $img) {
                $this->deleteImage($img);
            }
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}