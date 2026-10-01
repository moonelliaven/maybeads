<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest('id')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('category_name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_name' => ['required', 'string', 'max:100', 'unique:products,product_name'],
            'image' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('category_name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_name' => [
                'required',
                'string',
                'max:100',
                'unique:products,product_name,' . $product->id,
            ],
            'image' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
