<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan direktori gambar kategori di storage dan public tersedia
        $dirs = [
            storage_path('app/public/categories'),
            storage_path('app/public/category'),
            public_path('images/products'),
        ];

        foreach ($dirs as $dir) {
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
        }

        // Jalankan image processor jika file gambar belum ada
        if (!File::exists(storage_path('app/public/categories/cat-hairclip.png')) || filesize(storage_path('app/public/categories/cat-hairclip.png')) < 50000) {
            require_once __DIR__ . '/process_realistic_images.php';
        }

        $categories = [
            [
                'id'            => 1,
                'category_name' => 'Jepit Rambut',
                'image'         => 'categories/cat-hairclip.png',
            ],
            [
                'id'            => 2,
                'category_name' => 'Cincin',
                'image'         => 'categories/cat-ring.png',
            ],
            [
                'id'            => 3,
                'category_name' => 'Gelang Manik',
                'image'         => 'categories/cat-bracelet.png',
            ],
            [
                'id'            => 4,
                'category_name' => 'Kalung Manik',
                'image'         => 'categories/cat-necklace.png',
            ],
            [
                'id'            => 5,
                'category_name' => 'Phone Strap & Keychain',
                'image'         => 'categories/cat-strap.png',
            ],
        ];

        foreach ($categories as $category) {
            $cat = Category::withTrashed()->find($category['id']);
            if ($cat) {
                if ($cat->trashed()) {
                    $cat->restore();
                }
                $cat->update([
                    'category_name' => $category['category_name'],
                    'image'         => $category['image'],
                    'deleted_at'    => null,
                ]);
            } else {
                Category::create([
                    'id'            => $category['id'],
                    'category_name' => $category['category_name'],
                    'image'         => $category['image'],
                ]);
            }
        }
    }
}
