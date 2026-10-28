<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                'status'        => $p->status !== null ? (bool) $p->status : true,
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
            'product_name'       => 'required|string|max:100',
            'category_id'        => 'required',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'secondary_images'   => 'nullable|array|max:5',
            'secondary_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'        => 'nullable|string',
            'price'              => 'required|string|max:100',
            'stock'              => 'nullable|integer|min:0',
            'status'             => 'nullable',
            'variants'           => 'nullable|array',
            'variants.*.color'   => 'nullable|string|max:50',
            'variants.*.size'    => 'nullable|string|max:50',
            'variants.*.stock'   => 'nullable|integer|min:0',
            'variant_images.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Handle custom category
        $categoryId = $request->input('category_id');
        if ($categoryId === 'custom') {
            $customName = $request->input('custom_category_name');
            if ($customName) {
                $cat = Category::firstOrCreate(['category_name' => trim($customName)]);
                $categoryId = $cat->id;
            } else {
                throw ValidationException::withMessages([
                    'category_id' => 'Nama kategori custom wajib diisi.'
                ]);
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

        // Process & validate variants
        $rawVariants = $request->input('variants', []);
        $variantImages = $request->file('variant_images', []);
        $validatedVariants = [];
        $seenCombinations = [];

        foreach ($rawVariants as $index => $vData) {
            $color = trim($vData['color'] ?? '');
            $size  = trim($vData['size'] ?? '');
            $stock = isset($vData['stock']) ? max(0, (int) $vData['stock']) : 0;

            if ($color === '' && $size === '') {
                continue;
            }

            $comboKey = mb_strtolower($color) . '___' . mb_strtolower($size);
            if (isset($seenCombinations[$comboKey])) {
                throw ValidationException::withMessages([
                    'variants' => "Kombinasi varian warna '{$color}' dan ukuran '{$size}' tidak boleh duplikat."
                ]);
            }
            $seenCombinations[$comboKey] = true;

            $validatedVariants[] = [
                'index' => $index,
                'color' => $color !== '' ? $color : null,
                'size'  => $size !== '' ? $size : null,
                'stock' => $stock,
            ];
        }

        // Set total stock from variants if available
        if (!empty($validatedVariants)) {
            $validated['stock'] = array_sum(array_column($validatedVariants, 'stock'));
        } else {
            $validated['stock'] = (int) $request->input('stock', 0);
        }

        // Status
        $validated['status'] = $request->boolean('status');

        $product = Product::create($validated);

        // Save each variant
        foreach ($validatedVariants as $v) {
            $varImgName = null;
            if (isset($variantImages[$v['index']]) && $variantImages[$v['index']]->isValid()) {
                $varImgName = $this->storeImage($variantImages[$v['index']]);
            }

            ProductVariant::create([
                'product_id' => $product->id,
                'color'      => $v['color'],
                'size'       => $v['size'],
                'stock'      => $v['stock'],
                'image'      => $varImgName,
            ]);
        }

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'variants'])->findOrFail($id);
        return view('admin.product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'variants'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('variants')->findOrFail($id);

        $validated = $request->validate([
            'product_name'       => 'required|string|max:100',
            'category_id'        => 'required',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'secondary_images'   => 'nullable|array|max:5',
            'secondary_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'description'        => 'nullable|string',
            'price'              => 'required|string|max:100',
            'stock'              => 'nullable|integer|min:0',
            'status'             => 'nullable',
            'variants'           => 'nullable|array',
            'variants.*.color'   => 'nullable|string|max:50',
            'variants.*.size'    => 'nullable|string|max:50',
            'variants.*.stock'   => 'nullable|integer|min:0',
            'variant_images.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Handle custom category
        $categoryId = $request->input('category_id');
        if ($categoryId === 'custom') {
            $customName = $request->input('custom_category_name');
            if ($customName) {
                $cat = Category::firstOrCreate(['category_name' => trim($customName)]);
                $categoryId = $cat->id;
            } else {
                throw ValidationException::withMessages([
                    'category_id' => 'Nama kategori custom wajib diisi.'
                ]);
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

        // Secondary images
        $existingSec = $product->secondary_images ?? [];
        $removeSecIdx = $request->input('remove_secondary_images', []);
        foreach ($removeSecIdx as $idx) {
            if (isset($existingSec[$idx])) {
                $this->deleteImage($existingSec[$idx]);
                unset($existingSec[$idx]);
            }
        }
        $existingSec = array_values($existingSec);
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $f) {
                if (count($existingSec) < 5) {
                    $existingSec[] = $this->storeImage($f);
                }
            }
        }
        $validated['secondary_images'] = !empty($existingSec) ? $existingSec : null;

        // Process & validate variants
        $rawVariants = $request->input('variants', []);
        $variantImages = $request->file('variant_images', []);
        $validatedVariants = [];
        $seenCombinations = [];

        foreach ($rawVariants as $index => $vData) {
            $color = trim($vData['color'] ?? '');
            $size  = trim($vData['size'] ?? '');
            $stock = isset($vData['stock']) ? max(0, (int) $vData['stock']) : 0;

            if ($color === '' && $size === '') {
                continue;
            }

            $comboKey = mb_strtolower($color) . '___' . mb_strtolower($size);
            if (isset($seenCombinations[$comboKey])) {
                throw ValidationException::withMessages([
                    'variants' => "Kombinasi varian warna '{$color}' dan ukuran '{$size}' tidak boleh duplikat."
                ]);
            }
            $seenCombinations[$comboKey] = true;

            $validatedVariants[] = [
                'index'          => $index,
                'id'             => $vData['id'] ?? null,
                'color'          => $color !== '' ? $color : null,
                'size'           => $size !== '' ? $size : null,
                'stock'          => $stock,
                'existing_image' => $vData['existing_image'] ?? null,
            ];
        }

        // Set total stock from variants if available
        if (!empty($validatedVariants)) {
            $validated['stock'] = array_sum(array_column($validatedVariants, 'stock'));
        } else {
            $validated['stock'] = (int) $request->input('stock', 0);
        }

        // Status
        $validated['status'] = $request->boolean('status');

        $product->update($validated);

        // Sync Product Variants
        $existingVariants = $product->variants->keyBy('id');
        $keptVariantIds = [];

        foreach ($validatedVariants as $v) {
            $varId = $v['id'] ?? null;
            $imageName = $v['existing_image'] ?? null;

            // If a new image was uploaded for this variant index
            if (isset($variantImages[$v['index']]) && $variantImages[$v['index']]->isValid()) {
                if ($imageName) {
                    $this->deleteImage($imageName);
                }
                $imageName = $this->storeImage($variantImages[$v['index']]);
            }

            if ($varId && isset($existingVariants[$varId])) {
                $variantObj = $existingVariants[$varId];
                // If existing image was cleared by user and no new image was uploaded
                if (empty($v['existing_image']) && !isset($variantImages[$v['index']])) {
                    if ($variantObj->image) {
                        $this->deleteImage($variantObj->image);
                    }
                    $imageName = null;
                }

                $variantObj->update([
                    'color' => $v['color'],
                    'size'  => $v['size'],
                    'stock' => $v['stock'],
                    'image' => $imageName,
                ]);
                $keptVariantIds[] = (int) $varId;
            } else {
                $newVar = ProductVariant::create([
                    'product_id' => $product->id,
                    'color'      => $v['color'],
                    'size'       => $v['size'],
                    'stock'      => $v['stock'],
                    'image'      => $imageName,
                ]);
                $keptVariantIds[] = $newVar->id;
            }
        }

        // Delete variants that were removed
        foreach ($existingVariants as $oldId => $oldVar) {
            if (!in_array($oldId, $keptVariantIds)) {
                $this->deleteImage($oldVar->image);
                $oldVar->delete();
            }
        }

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($product)
    {
        if (! ($product instanceof Product)) {
            $product = Product::with('variants')->findOrFail($product);
        }

        $this->deleteImage($product->image);
        if ($product->secondary_images) {
            foreach ($product->secondary_images as $img) {
                $this->deleteImage($img);
            }
        }
        foreach ($product->variants as $v) {
            $this->deleteImage($v->image);
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}