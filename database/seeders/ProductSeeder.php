<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
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

        // Data produk dengan secondary_images
        $products = [
            [
                'category_id'      => 1,
                'product_name'     => 'Star Hairclip Y2K',
                'image'            => 'prod-hairclip.jpg',
                'secondary_images' => ['prod-ring.jpg', 'prod-butterfly.jpg'],
                'description'      => 'Jepit rambut aksen bintang krom gaya Y2K unik dan trendi. Dibuat dari material premium anti-karat dengan finishing chrome yang tahan lama. Cocok untuk tampilan harian maupun acara spesial.',
                'price'            => 35000,
                'stock'            => 20,
                'order_count'      => 6,
            ],
            [
                'category_id'      => 2,
                'product_name'     => 'Cyber Cross Ring',
                'image'            => 'prod-ring.jpg',
                'secondary_images' => ['prod-hairclip.jpg', 'prod-keychain.jpg'],
                'description'      => 'Cincin siluet gotik chrome perak kontemporer presisi tinggi. Desain geometrik yang terinspirasi dari estetika cyber-punk modern. Tersedia dalam berbagai ukuran untuk kenyamanan maksimal.',
                'price'            => 50000,
                'stock'            => 15,
                'order_count'      => 1,
            ],
            [
                'category_id'      => 3,
                'product_name'     => 'Butterfly Beads Bracelet',
                'image'            => 'prod-butterfly.jpg',
                'secondary_images' => ['prod-ring.jpg', 'prod-keychain.jpg'],
                'description'      => 'Gelang manik biru transparan dengan charm kupu-kupu aesthetic. Handmade dengan teknik anyaman khusus menggunakan manik kaca berkualitas tinggi. Ringan dan nyaman digunakan sepanjang hari.',
                'price'            => 45000,
                'stock'            => 25,
                'order_count'      => 1,
            ],
            [
                'category_id'      => 4,
                'product_name'     => 'Metallic Star Keychain',
                'image'            => 'prod-keychain.jpg',
                'secondary_images' => ['prod-butterfly.jpg', 'prod-hairclip.jpg'],
                'description'      => 'Gantungan kunci rantai logam perak bergaya cyber futuristik. Terbuat dari zinc alloy berkualitas tinggi dengan lapisan anti-karat. Cocok sebagai aksesoris maupun hadiah untuk orang tersayang.',
                'price'            => 38000,
                'stock'            => 30,
                'order_count'      => 1,
            ],
            [
                'category_id'      => 4,
                'product_name'     => 'Cyber Heart Keychain',
                'image'            => 'keychain-01.jpg',
                'secondary_images' => ['prod-keychain.jpg', 'prod-butterfly.jpg'],
                'description'      => 'Gantungan kunci hati chrome dengan rantai kokoh edisi terbatas. Finishing rose-gold dengan detail ukiran tangan yang halus. Dikemas dalam kotak premium siap hadiah.',
                'price'            => 42000,
                'stock'            => 18,
                'order_count'      => 1,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::updateOrCreate(
                ['product_name' => $productData['product_name']],
                collect($productData)->except('variants_data')->toArray()
            );
        }

        // Seed Variants untuk setiap produk
        $variantData = [
            'Star Hairclip Y2K' => [
                ['color' => 'Silver Chrome', 'size' => 'One Size', 'stock' => 10, 'image' => 'prod-hairclip.jpg'],
                ['color' => 'Gold Chrome',   'size' => 'One Size', 'stock' => 10, 'image' => 'prod-hairclip.jpg'],
            ],
            'Cyber Cross Ring' => [
                ['color' => 'Silver',  'size' => 'S (15mm)', 'stock' => 5,  'image' => 'prod-ring.jpg'],
                ['color' => 'Silver',  'size' => 'M (17mm)', 'stock' => 5,  'image' => 'prod-ring.jpg'],
                ['color' => 'Silver',  'size' => 'L (19mm)', 'stock' => 5,  'image' => 'prod-ring.jpg'],
            ],
            'Butterfly Beads Bracelet' => [
                ['color' => 'Biru Transparan', 'size' => 'S (16cm)', 'stock' => 10, 'image' => 'prod-butterfly.jpg'],
                ['color' => 'Biru Transparan', 'size' => 'M (18cm)', 'stock' => 10, 'image' => 'prod-butterfly.jpg'],
                ['color' => 'Pink Pastel',     'size' => 'S (16cm)', 'stock' => 5,  'image' => 'prod-butterfly.jpg'],
            ],
            'Metallic Star Keychain' => [
                ['color' => 'Silver',  'size' => 'Standard', 'stock' => 15, 'image' => 'prod-keychain.jpg'],
                ['color' => 'Gold',    'size' => 'Standard', 'stock' => 15, 'image' => 'prod-keychain.jpg'],
            ],
            'Cyber Heart Keychain' => [
                ['color' => 'Rose Gold', 'size' => 'Standard', 'stock' => 10, 'image' => 'keychain-01.jpg'],
                ['color' => 'Silver',    'size' => 'Standard', 'stock' => 8,  'image' => 'keychain-01.jpg'],
            ],
        ];

        foreach ($variantData as $productName => $variants) {
            $product = Product::where('product_name', $productName)->first();
            if (!$product) continue;

            // Hapus variant lama dan isi ulang
            ProductVariant::where('product_id', $product->id)->delete();

            foreach ($variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color'      => $v['color'],
                    'size'       => $v['size'],
                    'stock'      => $v['stock'],
                    'image'      => $v['image'],
                ]);
            }
        }
    }
}
