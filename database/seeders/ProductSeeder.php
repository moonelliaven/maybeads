<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Salin gambar ke storage
        $source = public_path('images/products');
        $target = storage_path('app/public/product');

        if (!File::isDirectory($target)) {
            File::makeDirectory($target, 0755, true, true);
        }

        if (File::isDirectory($source)) {
            foreach (File::files($source) as $file) {
                $destination = $target . '/' . $file->getFilename();

                if (!File::exists($destination)) {
                    File::copy($file->getPathname(), $destination);
                }
            }
        }

        // Data produk
        $products = [
            [
                'category_id' => 1,
                'product_name' => 'Star Hairclip Y2K',
                'image' => 'prod-hairclip.jpg',
                'description' => 'Jepit rambut aksen bintang krom gaya Y2K unik dan trendi.',
                'price' => '35.000',
                'stock' => 20,
            ],
            [
                'category_id' => 2,
                'product_name' => 'Cyber Cross Ring',
                'image' => 'prod-ring.jpg',
                'description' => 'Cincin siluet gotik chrome perak kontemporer presisi tinggi.',
                'price' => '50.000',
                'stock' => 15,
            ],
            [
                'category_id' => 3,
                'product_name' => 'Butterfly Beads Bracelet',
                'image' => 'prod-butterfly.jpg',
                'description' => 'Gelang manik biru transparan dengan charm kupu-kupu aesthetic.',
                'price' => '45.000',
                'stock' => 25,
            ],
            [
                'category_id' => 4,
                'product_name' => 'Metallic Star Keychain',
                'image' => 'prod-keychain.jpg',
                'description' => 'Gantungan kunci rantai logam perak bergaya cyber futuristik.',
                'price' => '38.000',
                'stock' => 30,
            ],
            [
                'category_id' => 4,
                'product_name' => 'Cyber Heart Keychain',
                'image' => 'keychain-01.jpg',
                'description' => 'Gantungan kunci hati chrome dengan rantai kokoh edisi terbatas.',
                'price' => '42.000',
                'stock' => 18,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['product_name' => $product['product_name']],
                $product
            );
        }
    }
}