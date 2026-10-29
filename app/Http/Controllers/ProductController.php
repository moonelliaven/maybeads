<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // -------------------show data---------------
    public function index()
    {
        $products = Product::with('category')->latest('id')->get();
        $categories = Category::all(['id', 'category_name']);

        return view('admin.product.index', compact('products', 'categories'));
    }

    // -------------------create data page---------------
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    // -------------------store data----------------
    public function store(Request $request)
    {
        if ($request->category_id === 'custom' && $request->filled('custom_category_name')) {
            $cat = Category::firstOrCreate(['category_name' => trim($request->custom_category_name)]);
            $request->merge(['category_id' => $cat->id]);
        }

        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'product_name'     => 'required|string|max:100|unique:products',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'      => 'nullable|string',
            'price'            => 'required',
            'stock'            => 'nullable|integer|min:0',
            'variants'         => 'nullable|array',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size'  => 'nullable|string|max:50',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        $data['status'] = $request->boolean('status') ? 'aktif' : 'tidak aktif';
        $data['price']  = (float) preg_replace('/[^0-9]/', '', (string) $request->price);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('product', $filename, 'public');
            $data['image'] = $filename;
        }

        DB::transaction(function () use ($data) {
            $product = Product::create($data);
            foreach ($data['variants'] ?? [] as $v) {
                if (!empty($v['color']) || !empty($v['size'])) {
                    $product->variants()->create([
                        'color' => $v['color'] ?? null,
                        'size'  => $v['size'] ?? null,
                        'stock' => (int) ($v['stock'] ?? 0),
                    ]);
                }
            }
        });

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // --------------------detail data----------------
    public function show(Product $product)
    {
        $product->load(['category', 'variants']);

        return view('admin.product.show', compact('product'));
    }

    // --------------edit page-----------------
    public function edit(Product $product)
    {
        $categories = Category::all(['id', 'category_name']);
        $product->load('variants');

        return view('admin.product.edit', compact('product', 'categories'));
    }

    // ------------------edit progress-----------------
    public function update(Request $request, Product $product)
    {
        if ($request->category_id === 'custom' && $request->filled('custom_category_name')) {
            $cat = Category::firstOrCreate(['category_name' => trim($request->custom_category_name)]);
            $request->merge(['category_id' => $cat->id]);
        }

        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'product_name'     => 'required|string|max:100|unique:products,product_name,' . $product->id,
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'      => 'nullable|string',
            'price'            => 'required',
            'stock'            => 'nullable|integer|min:0',
            'variants'         => 'nullable|array',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.size'  => 'nullable|string|max:50',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        $data['status'] = $request->boolean('status') ? 'aktif' : 'tidak aktif';
        $data['price']  = (float) preg_replace('/[^0-9]/', '', (string) $request->price);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('product', $filename, 'public');
            $data['image'] = $filename;
        } else {
            unset($data['image']);
        }

        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            if (isset($data['variants'])) {
                $product->variants()->delete();
                foreach ($data['variants'] as $v) {
                    if (!empty($v['color']) || !empty($v['size'])) {
                        $product->variants()->create([
                            'color' => $v['color'] ?? null,
                            'size'  => $v['size'] ?? null,
                            'stock' => (int) ($v['stock'] ?? 0),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // --------------------delete data----------------
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}