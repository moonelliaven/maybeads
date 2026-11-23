<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($c) {
                return [
                    'id'             => $c->id,
                    'category_name'  => $c->category_name,
                    'image'          => $c->image ? asset('storage/' . $c->image) : null,
                    'raw_image'      => $c->image,
                    'products_count' => (int) ($c->products_count ?? 0),
                    'products_stock' => (int) $c->products()->sum('stock'),
                    'created_at'     => $c->created_at ? $c->created_at->format('d M Y') : '-',
                ];
            });

        return view('admin.category.index', compact('categories'));
    }

    public function show($id)
    {
        $category = Category::withCount('products')
            ->with(['products' => function ($q) {
                $q->orderBy('id', 'desc');
            }])
            ->findOrFail($id);

        $totalStock = (int) $category->products()->sum('stock');

        return view('admin.category.show', compact('category', 'totalStock'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        Category::create($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->id,
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $category->update($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Soft delete (bisa dipulihkan di menu Restore dalam 30 hari)
        $name = $category->category_name;
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', "Kategori '{$name}' dipindahkan ke menu Restore (tersimpan selama 30 hari).");
    }
}
