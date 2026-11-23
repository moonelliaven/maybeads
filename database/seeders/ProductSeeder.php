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
        // Pastikan direktori gambar produk di storage dan public tersedia
        $dirs = [
            storage_path('app/public/product'),
            public_path('images/products'),
        ];

        foreach ($dirs as $dir) {
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
        }

        // Jalankan image processor jika file produk belum ada
        if (!File::exists(storage_path('app/public/product/y2k-prod-01.png')) || filesize(storage_path('app/public/product/y2k-prod-01.png')) < 50000) {
            require_once __DIR__ . '/process_realistic_images.php';
        }

        // Hapus produk lama dengan ID di luar range 1-32 jika tidak memiliki order
        $validIds = range(1, 32);
        ProductVariant::whereNotIn('product_id', $validIds)->delete();
        Product::whereNotIn('id', $validIds)->forceDelete();

        // Daftar 32 Produk Y2K Aksesoris Maybeads
        $products = [
            // ==========================================
            // KATEGORI 1: Jepit Rambut (Hair Clips & Pins)
            // ==========================================
            [
                'id'               => 1,
                'category_id'      => 1,
                'product_name'     => 'Star Hairclip Chrome Y2K',
                'image'            => 'y2k-prod-01.png',
                'secondary_images' => ['y2k-prod-01-sec1.png', 'y2k-prod-01-sec2.png'],
                'description'      => 'Jepit rambut aksen bintang krom gaya Y2K unik dan trendi. Dibuat dari alloy premium anti-karat dengan finishing chrome reflektif. Memberikan sentuhan retro futuristik yang ikonik untuk gaya rambut harian maupun festival.',
                'price'            => 35000,
                'stock'            => 25,
                'order_count'      => 14,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver Chrome', 'size' => 'Standard', 'stock' => 15, 'image' => 'y2k-prod-01-var1.png'],
                    ['color' => 'Holo Pink',     'size' => 'Standard', 'stock' => 10, 'image' => 'y2k-prod-01-var2.png'],
                ],
            ],
            [
                'id'               => 2,
                'category_id'      => 1,
                'product_name'     => 'Butterfly Metallic Snap Clip',
                'image'            => 'y2k-prod-02.png',
                'secondary_images' => ['y2k-prod-02-sec1.png', 'y2k-prod-02-sec2.png'],
                'description'      => 'Snap clip kupu-kupu logam bergaya 2000s dengan aksen glitter halus dan daya cengkeram kuat. Tidak merusak helaian rambut dan pas dipadukan dengan gaya twin-braids atau space buns.',
                'price'            => 28000,
                'stock'            => 30,
                'order_count'      => 19,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Metallic Lilac', 'size' => 'Set of 2', 'stock' => 15, 'image' => 'y2k-prod-02-var1.png'],
                    ['color' => 'Chrome Blue',    'size' => 'Set of 2', 'stock' => 15, 'image' => 'y2k-prod-02-var2.png'],
                ],
            ],
            [
                'id'               => 3,
                'category_id'      => 1,
                'product_name'     => 'Cyber Glitz Heart Hairpin',
                'image'            => 'y2k-prod-03.png',
                'secondary_images' => ['y2k-prod-03-sec1.png', 'y2k-prod-03-sec2.png'],
                'description'      => 'Hairpin bentuk hati cyber bertekstur kristal holografik. Kilauan permata imitasi memantulkan cahaya indah dari segala sudut, sempurna untuk estetika cyber-fairy Y2K.',
                'price'            => 32000,
                'stock'            => 20,
                'order_count'      => 8,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Neon Pink',      'size' => 'One Size', 'stock' => 10, 'image' => 'y2k-prod-03-var1.png'],
                    ['color' => 'Silver Glitter', 'size' => 'One Size', 'stock' => 10, 'image' => 'y2k-prod-03-var2.png'],
                ],
            ],
            [
                'id'               => 4,
                'category_id'      => 1,
                'product_name'     => 'Pastel Beads Bobby Pin Set',
                'image'            => 'y2k-prod-04.png',
                'secondary_images' => ['y2k-prod-04-sec1.png', 'y2k-prod-04-sec2.png'],
                'description'      => 'Set 4 bobby pin dengan deretan manik pastel manis dan mutiara air tawar imitasi. Anyaman manik rapi dan kokoh, menambahkan aksen imut yang ceria pada tatanan rambut.',
                'price'            => 24000,
                'stock'            => 40,
                'order_count'      => 22,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Candy Pastel', 'size' => 'Set of 4', 'stock' => 20, 'image' => 'y2k-prod-04-var1.png'],
                    ['color' => 'Fairy Mint',   'size' => 'Set of 4', 'stock' => 20, 'image' => 'y2k-prod-04-var2.png'],
                ],
            ],
            [
                'id'               => 5,
                'category_id'      => 1,
                'product_name'     => 'Y2K Hologram Star Barrette',
                'image'            => 'y2k-prod-05.png',
                'secondary_images' => ['y2k-prod-05-sec1.png', 'y2k-prod-05-sec2.png'],
                'description'      => 'Jepit barrette bintang holografik memantulkan spektrum warna warni saat terkena pantulan cahaya. Bahan akrilik tebal tahan pecah dengan pegas jepit berlapis krom.',
                'price'            => 36000,
                'stock'            => 18,
                'order_count'      => 11,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Iridescent Holo', 'size' => 'Medium', 'stock' => 10, 'image' => 'y2k-prod-05-var1.png'],
                    ['color' => 'Cyber Purple',    'size' => 'Medium', 'stock' => 8,  'image' => 'y2k-prod-05-var2.png'],
                ],
            ],
            [
                'id'               => 6,
                'category_id'      => 1,
                'product_name'     => 'Angel Wings Mini Claw Clip',
                'image'            => 'y2k-prod-06.png',
                'secondary_images' => ['y2k-prod-06-sec1.png', 'y2k-prod-06-sec2.png'],
                'description'      => 'Mini claw clip berbentuk sayap malaikat yang menggemaskan dengan sentuhan kilau mutiara halus. Ringan digunakan dan cocok untuk menjepit poni atau aksen rambut samping.',
                'price'            => 27000,
                'stock'            => 22,
                'order_count'      => 7,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Pearl White', 'size' => 'Mini Pair', 'stock' => 12, 'image' => 'y2k-prod-06-var1.png'],
                    ['color' => 'Baby Blue',   'size' => 'Mini Pair', 'stock' => 10, 'image' => 'y2k-prod-06-var2.png'],
                ],
            ],

            // ==========================================
            // KATEGORI 2: Cincin (Y2K Rings)
            // ==========================================
            [
                'id'               => 7,
                'category_id'      => 2,
                'product_name'     => 'Cyber Cross Silver Ring',
                'image'            => 'y2k-prod-07.png',
                'secondary_images' => ['y2k-prod-07-sec1.png', 'y2k-prod-07-sec2.png'],
                'description'      => 'Cincin siluet salib gotik cyber bernuansa perak futuristik dengan detail presisi tinggi. Material titanium steel anti-karat dan tidak menimbulkan alergi pada kulit sensitif.',
                'price'            => 45000,
                'stock'            => 20,
                'order_count'      => 16,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver Chrome',   'size' => 'Adjustable (15-18mm)', 'stock' => 10, 'image' => 'y2k-prod-07-var1.png'],
                    ['color' => 'Obsidian Chrome', 'size' => 'Adjustable (15-18mm)', 'stock' => 10, 'image' => 'y2k-prod-07-var2.png'],
                ],
            ],
            [
                'id'               => 8,
                'category_id'      => 2,
                'product_name'     => 'Chunky Resin Heart Ring',
                'image'            => 'y2k-prod-08.png',
                'secondary_images' => ['y2k-prod-08-sec1.png', 'y2k-prod-08-sec2.png'],
                'description'      => 'Cincin resin tebal khas era 2000s dengan glitter hati tertanam di dalamnya. Finishing halus dan glossy, sangat playful untuk layering aksesoris jari.',
                'price'            => 25000,
                'stock'            => 35,
                'order_count'      => 27,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Bubblegum Pink', 'size' => 'Free Size (16-18mm)', 'stock' => 20, 'image' => 'y2k-prod-08-var1.png'],
                    ['color' => 'Lime Sparkle',   'size' => 'Free Size (16-18mm)', 'stock' => 15, 'image' => 'y2k-prod-08-var2.png'],
                ],
            ],
            [
                'id'               => 9,
                'category_id'      => 2,
                'product_name'     => 'Metallic Thorn Band Ring',
                'image'            => 'y2k-prod-09.png',
                'secondary_images' => ['y2k-prod-09-sec1.png', 'y2k-prod-09-sec2.png'],
                'description'      => 'Cincin band motif duri cair (liquid thorn) ala cybercore grunge yang edgy. Desain open-ring adjustable sehingga dapat disesuaikan dengan mudah di berbagai jari.',
                'price'            => 48000,
                'stock'            => 15,
                'order_count'      => 9,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Matte Silver',  'size' => 'Adjustable', 'stock' => 8, 'image' => 'y2k-prod-09-var1.png'],
                    ['color' => 'Glossy Chrome', 'size' => 'Adjustable', 'stock' => 7, 'image' => 'y2k-prod-09-var2.png'],
                ],
            ],
            [
                'id'               => 10,
                'category_id'      => 2,
                'product_name'     => 'Pastel Daisy Beaded Ring',
                'image'            => 'y2k-prod-10.png',
                'secondary_images' => ['y2k-prod-10-sec1.png', 'y2k-prod-10-sec2.png'],
                'description'      => 'Cincin anyaman manik handmade motif bunga daisy pastel elastis. Ringan dan manis, dibuat menggunakan senar elastis Korea berkualitas tinggi yang tidak mudah kendur.',
                'price'            => 18000,
                'stock'            => 50,
                'order_count'      => 32,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Daisy Sunshine Yellow', 'size' => 'Elastic (15-17mm)', 'stock' => 25, 'image' => 'y2k-prod-10-var1.png'],
                    ['color' => 'Daisy Soft Lavender',   'size' => 'Elastic (15-17mm)', 'stock' => 25, 'image' => 'y2k-prod-10-var2.png'],
                ],
            ],
            [
                'id'               => 11,
                'category_id'      => 2,
                'product_name'     => 'Alien Glow Mood Ring',
                'image'            => 'y2k-prod-11.png',
                'secondary_images' => ['y2k-prod-11-sec1.png', 'y2k-prod-11-sec2.png'],
                'description'      => 'Cincin mood ring legendaris yang dapat berubah warna mengikuti perubahan suhu emosi dan tubuh. Dihiasi frame alien metalik retro bergaya alien-core Y2K.',
                'price'            => 38000,
                'stock'            => 25,
                'order_count'      => 12,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Color-Shift Thermic', 'size' => 'Adjustable', 'stock' => 15, 'image' => 'y2k-prod-11-var1.png'],
                    ['color' => 'Alien Emerald Glow',  'size' => 'Adjustable', 'stock' => 10, 'image' => 'y2k-prod-11-var2.png'],
                ],
            ],
            [
                'id'               => 12,
                'category_id'      => 2,
                'product_name'     => 'Silver Liquid Drip Ring',
                'image'            => 'y2k-prod-12.png',
                'secondary_images' => ['y2k-prod-12-sec1.png', 'y2k-prod-12-sec2.png'],
                'description'      => 'Cincin statement bernuansa logam cair meleleh (molten metal) yang memancarkan aura futuristik avant-garde. Cocok untuk aksen utama tampilan fashion street-style.',
                'price'            => 52000,
                'stock'            => 16,
                'order_count'      => 15,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver Chrome',   'size' => 'M (17mm)', 'stock' => 8, 'image' => 'y2k-prod-12-var1.png'],
                    ['color' => 'Cyber Gunmetal',  'size' => 'M (17mm)', 'stock' => 8, 'image' => 'y2k-prod-12-var2.png'],
                ],
            ],

            // ==========================================
            // KATEGORI 3: Gelang Manik (Beaded Bracelets)
            // ==========================================
            [
                'id'               => 13,
                'category_id'      => 3,
                'product_name'     => 'Butterfly Glass Beads Bracelet',
                'image'            => 'y2k-prod-13.png',
                'secondary_images' => ['y2k-prod-13-sec1.png', 'y2k-prod-13-sec2.png'],
                'description'      => 'Gelang manik kaca transparan dengan liontin kupu-kupu akrilik dan butiran mutiara imitasi. Tali elastis ganda yang kuat, memancarkan kilau air jernih yang memukau.',
                'price'            => 42000,
                'stock'            => 30,
                'order_count'      => 24,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Blue Ocean Glass',  'size' => '16-17 cm', 'stock' => 15, 'image' => 'y2k-prod-13-var1.png'],
                    ['color' => 'Sunset Peach Glow', 'size' => '16-17 cm', 'stock' => 15, 'image' => 'y2k-prod-13-var2.png'],
                ],
            ],
            [
                'id'               => 14,
                'category_id'      => 3,
                'product_name'     => 'Cyber Star Beaded Bracelet',
                'image'            => 'y2k-prod-14.png',
                'secondary_images' => ['y2k-prod-14-sec1.png', 'y2k-prod-14-sec2.png'],
                'description'      => 'Kombinasi manik bintang metalik, beads transparan, dan tali elastis premium. Dirancang dengan komposisi warna kontras cyber Y2K yang membuat pergelangan tangan tampak menonjol.',
                'price'            => 39000,
                'stock'            => 28,
                'order_count'      => 18,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver & Black Void', 'size' => 'Standard (17cm)', 'stock' => 14, 'image' => 'y2k-prod-14-var1.png'],
                    ['color' => 'Cyber Neon Pink',     'size' => 'Standard (17cm)', 'stock' => 14, 'image' => 'y2k-prod-14-var2.png'],
                ],
            ],
            [
                'id'               => 15,
                'category_id'      => 3,
                'product_name'     => 'Pastel Pearl Smile Charm',
                'image'            => 'y2k-prod-15.png',
                'secondary_images' => ['y2k-prod-15-sec1.png', 'y2k-prod-15-sec2.png'],
                'description'      => 'Gelang manik mutiara ceria dihiasi charm smile face dan mutiara pastel pelangi. Sentuhan nostalgia yang hangat dan penuh energi positif untuk teman aktivitas sehari-hari.',
                'price'            => 34000,
                'stock'            => 45,
                'order_count'      => 29,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Pastel Rainbow Mix',  'size' => 'One Size', 'stock' => 25, 'image' => 'y2k-prod-15-var1.png'],
                    ['color' => 'Soft Creamy Vanilla', 'size' => 'One Size', 'stock' => 20, 'image' => 'y2k-prod-15-var2.png'],
                ],
            ],
            [
                'id'               => 16,
                'category_id'      => 3,
                'product_name'     => 'Fairycore Crystal Beads Bangle',
                'image'            => 'y2k-prod-16.png',
                'secondary_images' => ['y2k-prod-16-sec1.png', 'y2k-prod-16-sec2.png'],
                'description'      => 'Gelang bangle manik kristal faset berkilau ala peri Y2K. Refleksi kilau kristal faset memberikan aksen glamor namun tetap youthful dan anggun.',
                'price'            => 46000,
                'stock'            => 20,
                'order_count'      => 13,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Lavender Mist', 'size' => 'Medium (17cm)', 'stock' => 10, 'image' => 'y2k-prod-16-var1.png'],
                    ['color' => 'Emerald Glint', 'size' => 'Medium (17cm)', 'stock' => 10, 'image' => 'y2k-prod-16-var2.png'],
                ],
            ],
            [
                'id'               => 17,
                'category_id'      => 3,
                'product_name'     => 'Retro Pixel Heart Bracelet',
                'image'            => 'y2k-prod-17.png',
                'secondary_images' => ['y2k-prod-17-sec1.png', 'y2k-prod-17-sec2.png'],
                'description'      => 'Gelang manik tali serut dengan liontin hati berdesain 8-bit pixel game era arcade 90s-2000s. Ukuran tali mudah disesuaikan untuk berbagai lingkar pergelangan tangan.',
                'price'            => 33000,
                'stock'            => 35,
                'order_count'      => 21,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => '8-Bit Crimson Red', 'size' => 'Adjustable Cord', 'stock' => 18, 'image' => 'y2k-prod-17-var1.png'],
                    ['color' => 'Cyber Violet',      'size' => 'Adjustable Cord', 'stock' => 17, 'image' => 'y2k-prod-17-var2.png'],
                ],
            ],
            [
                'id'               => 18,
                'category_id'      => 3,
                'product_name'     => 'Galaxy Glow Beaded Armband',
                'image'            => 'y2k-prod-18.png',
                'secondary_images' => ['y2k-prod-18-sec1.png', 'y2k-prod-18-sec2.png'],
                'description'      => 'Gelang manik glow in the dark yang dapat berpendar lembut dalam kegelapan setelah menyerap cahaya. Dipadukan dengan aksen beads metalik luar angkasa.',
                'price'            => 40000,
                'stock'            => 24,
                'order_count'      => 17,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Deep Space Blue Glow', 'size' => '17 cm', 'stock' => 12, 'image' => 'y2k-prod-18-var1.png'],
                    ['color' => 'Nebula Pink Glow',     'size' => '17 cm', 'stock' => 12, 'image' => 'y2k-prod-18-var2.png'],
                ],
            ],

            // ==========================================
            // KATEGORI 4: Kalung Manik (Beaded Necklaces)
            // ==========================================
            [
                'id'               => 19,
                'category_id'      => 4,
                'product_name'     => 'Y2K Silver Star Choker',
                'image'            => 'y2k-prod-19.png',
                'secondary_images' => ['y2k-prod-19-sec1.png', 'y2k-prod-19-sec2.png'],
                'description'      => 'Choker rantai kombinasi manik bintang perak tebal dengan lobster clasp anti-karat. Dilengkapi rantai perpanjangan 5 cm sehingga nyaman diatur panjangnya.',
                'price'            => 58000,
                'stock'            => 15,
                'order_count'      => 20,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver Chrome Star', 'size' => '38cm + 5cm Extender', 'stock' => 8, 'image' => 'y2k-prod-19-var1.png'],
                    ['color' => 'Black Cyber Cord',   'size' => '38cm + 5cm Extender', 'stock' => 7, 'image' => 'y2k-prod-19-var2.png'],
                ],
            ],
            [
                'id'               => 20,
                'category_id'      => 4,
                'product_name'     => 'Candy Heart Pastel Necklace',
                'image'            => 'y2k-prod-20.png',
                'secondary_images' => ['y2k-prod-20-sec1.png', 'y2k-prod-20-sec2.png'],
                'description'      => 'Kalung manik-manik pastel warna-warni ceria dengan pendant permen hati bertuliskan retro Y2K. Manis, playful, dan sangat cocok untuk layering kalung berjenjang.',
                'price'            => 49000,
                'stock'            => 25,
                'order_count'      => 15,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Cotton Candy Dream', 'size' => '40cm + 5cm', 'stock' => 13, 'image' => 'y2k-prod-20-var1.png'],
                    ['color' => 'Berry Sweet Mix',    'size' => '40cm + 5cm', 'stock' => 12, 'image' => 'y2k-prod-20-var2.png'],
                ],
            ],
            [
                'id'               => 21,
                'category_id'      => 4,
                'product_name'     => 'Cyberpunk Cross Pearl Choker',
                'image'            => 'y2k-prod-21.png',
                'secondary_images' => ['y2k-prod-21-sec1.png', 'y2k-prod-21-sec2.png'],
                'description'      => 'Paduan separuh mutiara klasik dan separuh rantai industrial tebal dengan liontin salib cyber. Menyatukan keanggunan vintage dan ketegasan futuristik street fashion.',
                'price'            => 65000,
                'stock'            => 18,
                'order_count'      => 26,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Half-Pearl Silver Chain', 'size' => '42cm', 'stock' => 10, 'image' => 'y2k-prod-21-var1.png'],
                    ['color' => 'Dark Pearl Gunmetal',     'size' => '42cm', 'stock' => 8,  'image' => 'y2k-prod-21-var2.png'],
                ],
            ],
            [
                'id'               => 22,
                'category_id'      => 4,
                'product_name'     => 'Butterfly Garden Beads Choker',
                'image'            => 'y2k-prod-22.png',
                'secondary_images' => ['y2k-prod-22-sec1.png', 'y2k-prod-22-sec2.png'],
                'description'      => 'Kalung manik kristal kaca bertabur liontin kupu-kupu akrilik melayang. Komposisi manik dibuat bertingkat dengan detail ketelitian tinggi pengrajin Maybeads.',
                'price'            => 54000,
                'stock'            => 20,
                'order_count'      => 12,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Lilac Fairy Garden', 'size' => '40cm + 5cm', 'stock' => 10, 'image' => 'y2k-prod-22-var1.png'],
                    ['color' => 'Aqua Sky Breeze',    'size' => '40cm + 5cm', 'stock' => 10, 'image' => 'y2k-prod-22-var2.png'],
                ],
            ],
            [
                'id'               => 23,
                'category_id'      => 4,
                'product_name'     => 'Liquid Metal Heart Choker',
                'image'            => 'y2k-prod-23.png',
                'secondary_images' => ['y2k-prod-23-sec1.png', 'y2k-prod-23-sec2.png'],
                'description'      => 'Kalung choker tali beludru hitam berpadu pendant hati logam meleleh gaya avant-garde 2000s. Memberikan kesan misterius dan elegan sekaligus.',
                'price'            => 62000,
                'stock'            => 14,
                'order_count'      => 16,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Molten Chrome Silver', 'size' => '36cm + 7cm', 'stock' => 7, 'image' => 'y2k-prod-23-var1.png'],
                    ['color' => 'Rose Gold Metallic',   'size' => '36cm + 7cm', 'stock' => 7, 'image' => 'y2k-prod-23-var2.png'],
                ],
            ],
            [
                'id'               => 24,
                'category_id'      => 4,
                'product_name'     => 'Iridescent Star Pearl Necklace',
                'image'            => 'y2k-prod-24.png',
                'secondary_images' => ['y2k-prod-24-sec1.png', 'y2k-prod-24-sec2.png'],
                'description'      => 'Kalung mutiara berkilau pelangi dengan aksen bintang akrilik bening yang membiaskan spektrum pelangi. Menggunakan benang khusus anti-putus.',
                'price'            => 56000,
                'stock'            => 22,
                'order_count'      => 11,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Opal Iridescent White', 'size' => '41cm + 5cm', 'stock' => 12, 'image' => 'y2k-prod-24-var1.png'],
                    ['color' => 'Aurora Borealis Glint', 'size' => '41cm + 5cm', 'stock' => 10, 'image' => 'y2k-prod-24-var2.png'],
                ],
            ],

            // ==========================================
            // KATEGORI 5: Phone Strap & Keychain (Charms & Lanyards)
            // ==========================================
            [
                'id'               => 25,
                'category_id'      => 5,
                'product_name'     => 'Metallic Star Cyber Keychain',
                'image'            => 'y2k-prod-25.png',
                'secondary_images' => ['y2k-prod-25-sec1.png', 'y2k-prod-25-sec2.png'],
                'description'      => 'Gantungan kunci rantai logam perak bergaya cyber futuristik dengan bintang ganda yang solid. Dilengkapi ring putar 360 derajat tahan karat untuk tas atau celana jeans.',
                'price'            => 38000,
                'stock'            => 30,
                'order_count'      => 23,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Silver Mirror Chrome', 'size' => '12 cm', 'stock' => 15, 'image' => 'y2k-prod-25-var1.png'],
                    ['color' => 'Gunmetal Shadow',      'size' => '12 cm', 'stock' => 15, 'image' => 'y2k-prod-25-var2.png'],
                ],
            ],
            [
                'id'               => 26,
                'category_id'      => 5,
                'product_name'     => 'Y2K Angel Heart Phone Strap',
                'image'            => 'y2k-prod-26.png',
                'secondary_images' => ['y2k-prod-26-sec1.png', 'y2k-prod-26-sec2.png'],
                'description'      => 'Tali gantungan ponsel bertema malaikat dengan manik sayap, mutiara, dan charm hati akrilik bening. Tali pengait tebal dan kuat untuk menahan bobot smartphone.',
                'price'            => 32000,
                'stock'            => 40,
                'order_count'      => 35,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Angelic Pure White', 'size' => '15 cm Strap', 'stock' => 20, 'image' => 'y2k-prod-26-var1.png'],
                    ['color' => 'Sweet Angel Pink',   'size' => '15 cm Strap', 'stock' => 20, 'image' => 'y2k-prod-26-var2.png'],
                ],
            ],
            [
                'id'               => 27,
                'category_id'      => 5,
                'product_name'     => 'Kawaii Gummy Bear Phone Strap',
                'image'            => 'y2k-prod-27.png',
                'secondary_images' => ['y2k-prod-27-sec1.png', 'y2k-prod-27-sec2.png'],
                'description'      => 'Phone strap ceria dengan manik permen dan liontin beruang gummy bear jelly yang super imut. Memberikan aksen warna pop yang menyenangkan pada casing smartphone Anda.',
                'price'            => 29000,
                'stock'            => 35,
                'order_count'      => 31,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Gummy Bear Strawberry', 'size' => '14 cm', 'stock' => 18, 'image' => 'y2k-prod-27-var1.png'],
                    ['color' => 'Gummy Bear Blueberry',  'size' => '14 cm', 'stock' => 17, 'image' => 'y2k-prod-27-var2.png'],
                ],
            ],
            [
                'id'               => 28,
                'category_id'      => 5,
                'product_name'     => 'Holographic Butterfly Keychain',
                'image'            => 'y2k-prod-28.png',
                'secondary_images' => ['y2k-prod-28-sec1.png', 'y2k-prod-28-sec2.png'],
                'description'      => 'Keychain kupu-kupu akrilik holografik dengan rumbai manik kaca dan kait putar yang kokoh. Efek pelangi berkilau indah saat terkena cahaya matahari.',
                'price'            => 35000,
                'stock'            => 28,
                'order_count'      => 14,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Holographic Silver', 'size' => '11 cm', 'stock' => 14, 'image' => 'y2k-prod-28-var1.png'],
                    ['color' => 'Holo Magenta Flare', 'size' => '11 cm', 'stock' => 14, 'image' => 'y2k-prod-28-var2.png'],
                ],
            ],
            [
                'id'               => 29,
                'category_id'      => 5,
                'product_name'     => 'Cyber Matrix Beaded Lanyard',
                'image'            => 'y2k-prod-29.png',
                'secondary_images' => ['y2k-prod-29-sec1.png', 'y2k-prod-29-sec2.png'],
                'description'      => 'Lanyard pergelangan tangan untuk smartphone dengan kombinasi manik dadu angka, mutiara, dan tali paracord kuat. Praktis dan trendi dibawa bepergian.',
                'price'            => 45000,
                'stock'            => 20,
                'order_count'      => 19,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Matrix Neon Green', 'size' => 'Wrist Strap (18cm)', 'stock' => 10, 'image' => 'y2k-prod-29-var1.png'],
                    ['color' => 'Cyber Aqua Blue',   'size' => 'Wrist Strap (18cm)', 'stock' => 10, 'image' => 'y2k-prod-29-var2.png'],
                ],
            ],
            [
                'id'               => 30,
                'category_id'      => 5,
                'product_name'     => 'Glow In Dark Star Charm',
                'image'            => 'y2k-prod-30.png',
                'secondary_images' => ['y2k-prod-30-sec1.png', 'y2k-prod-30-sec2.png'],
                'description'      => 'Gantungan charm tas dan dompet dengan bintang glow in the dark yang menyala di tempat gelap. Menjadikan tas atau pouch Anda mudah ditemukan sekaligus tampil estetik.',
                'price'            => 31000,
                'stock'            => 35,
                'order_count'      => 22,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Glow Krypton Green', 'size' => '10 cm', 'stock' => 18, 'image' => 'y2k-prod-30-var1.png'],
                    ['color' => 'Glow Cosmic Blue',   'size' => '10 cm', 'stock' => 17, 'image' => 'y2k-prod-30-var2.png'],
                ],
            ],
            [
                'id'               => 31,
                'category_id'      => 5,
                'product_name'     => 'Bubblegum Heart Beaded Strap',
                'image'            => 'y2k-prod-31.png',
                'secondary_images' => ['y2k-prod-31-sec1.png', 'y2k-prod-31-sec2.png'],
                'description'      => 'Strap manik warna permen karet manis dengan liontin hati resin transparan bertabur serpihan gliter. Desain girly retro yang sangat disukai penggemar estetika Y2K.',
                'price'            => 33000,
                'stock'            => 25,
                'order_count'      => 28,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Bubblegum Hot Pink', 'size' => '15 cm', 'stock' => 13, 'image' => 'y2k-prod-31-var1.png'],
                    ['color' => 'Lilac Marshmallow',  'size' => '15 cm', 'stock' => 12, 'image' => 'y2k-prod-31-var2.png'],
                ],
            ],
            [
                'id'               => 32,
                'category_id'      => 5,
                'product_name'     => 'Retro Tamagotchi Keychain',
                'image'            => 'y2k-prod-32.png',
                'secondary_images' => ['y2k-prod-32-sec1.png', 'y2k-prod-32-sec2.png'],
                'description'      => 'Keychain nostalgia dengan charm miniatur konsol virtual pet era 2000s dan gantungan manik pixel. Koleksi wajib untuk pecinta vibe 90s-2000s.',
                'price'            => 44000,
                'stock'            => 22,
                'order_count'      => 25,
                'status'           => 'aktif',
                'variants'         => [
                    ['color' => 'Retro Neon Yellow', 'size' => '13 cm', 'stock' => 11, 'image' => 'y2k-prod-32-var1.png'],
                    ['color' => 'Cyber Pastel Teal', 'size' => '13 cm', 'stock' => 11, 'image' => 'y2k-prod-32-var2.png'],
                ],
            ],
        ];

        foreach ($products as $pData) {
            $variants = $pData['variants'] ?? [];
            unset($pData['variants']);

            $product = Product::withTrashed()->find($pData['id']);
            if ($product) {
                if ($product->trashed()) {
                    $product->restore();
                }
                $product->update($pData);
            } else {
                $product = Product::create($pData);
            }

            // Re-seed variants
            if (!empty($variants)) {
                ProductVariant::where('product_id', $product->id)->delete();

                foreach ($variants as $v) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color'      => $v['color'] ?? 'Standard',
                        'size'       => $v['size'] ?? 'One Size',
                        'stock'      => $v['stock'] ?? 10,
                        'image'      => $v['image'] ?? $product->image,
                    ]);
                }
            }
        }
    }
}
