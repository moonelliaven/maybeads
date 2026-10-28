<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->withSum('products', 'stock')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($c) {
                return [
                    'id'             => $c->id,
                    'category_name'  => $c->category_name,
                    'products_count' => (int) ($c->products_count ?? 0),
                    'products_stock' => (int) ($c->products_sum_stock ?? 0),
                    'created_at'     => $c->created_at ? $c->created_at->format('d M Y') : '-',
                ];
            });

        return view('admin.category.index', compact('categories'));
    }

    public function show($id)
    {
        $category = Category::withCount('products')
            ->withSum('products', 'stock')
            ->with(['products' => function ($q) {
                $q->orderBy('id', 'desc');
            }])
            ->findOrFail($id);

        return view('admin.category.show', compact('category'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
        ]);

        Category::create($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'image'         => 'nullable|string|max:50',
        ]);

        $category->update($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak bisa dihapus karena masih digunakan oleh produk.'
            );
        }

        
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
