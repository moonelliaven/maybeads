<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::where('role', 'user')->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command?->warn('User atau produk kosong, skip TestimonialSeeder.');
            return;
        }

        // Testimoni umum untuk TOKO (is_store_testimonial = true, product_id = null)
        $storeTestimonials = [
            [
                'username' => 'moonelliaven',
                'message'  => 'Belanja di Maybeads selalu menyenangkan! Pelayanan ramah, packaging rapi, dan produk selalu sesuai foto. Sudah langganan sejak lama dan tidak pernah kecewa.',
                'rating'   => 5,
                'days_ago' => 5,
            ],
            [
                'username' => 'trantrungkien',
                'message'  => 'Pengiriman cepat banget, baru order langsung dikirim hari itu juga. Kualitas produk premium. Pasti balik lagi!',
                'rating'   => 5,
                'days_ago' => 12,
            ],
            [
                'username' => 'trandinhtuan567',
                'message'  => 'Harga terjangkau tapi kualitas tidak murahan. Toko ini juaranya aksesoris handmade Y2K di kelasnya!',
                'rating'   => 5,
                'days_ago' => 20,
            ],
            [
                'username' => 'nguyenvinhhoa567',
                'message'  => 'Sudah order berkali-kali, dan selalu puas. Produknya awet dan detail finishing sangat rapi.',
                'rating'   => 4,
                'days_ago' => 30,
            ],
            [
                'username' => 'hoangthanhphat88',
                'message'  => 'Recommended banget buat yang suka aksesoris unik. Owner juga responsif dan helpful.',
                'rating'   => 5,
                'days_ago' => 45,
            ],
        ];

        foreach ($storeTestimonials as $t) {
            $user = $users->firstWhere('name', $t['username']);
            if (!$user) $user = $users->first();

            Testimonial::updateOrCreate(
                [
                    'user_id'              => $user->id,
                    'product_id'           => null,
                    'is_store_testimonial' => true,
                    'message'              => $t['message'],
                ],
                [
                    'rating'     => $t['rating'],
                    'created_at' => Carbon::now()->subDays($t['days_ago']),
                    'updated_at' => Carbon::now()->subDays($t['days_ago']),
                ]
            );
        }

        // Testimoni per PRODUK (is_store_testimonial = false, product_id = product id)
        $productTestimonials = [
            [
                'product_name' => 'Star Hairclip Y2K',
                'testimonials' => [
                    ['username' => 'moonelliaven',    'rating' => 5, 'message' => 'Jepit rambutnya lucu banget! Kualitas chrome-nya bagus, tidak mudah pudar. Sudah pakai 2 bulan masih kinclong.', 'days_ago' => 7],
                    ['username' => 'trandinhtuan567',  'rating' => 5, 'message' => 'Model bintangnya aesthetic banget, cocok untuk gaya Y2K aku. Sudah beli 3 warna!', 'days_ago' => 15],
                    ['username' => 'nguyenvinhhoa567', 'rating' => 4, 'message' => 'Bagus dan lucu, hanya packaging sedikit kurang. Tapi produknya oke banget.', 'days_ago' => 25],
                ],
            ],
            [
                'product_name' => 'Cyber Cross Ring',
                'testimonials' => [
                    ['username' => 'trantrungkien', 'rating' => 5, 'message' => 'Cincinnya keren abis! Desain gotik-nya pas banget, tidak terlalu norak. Ukuran M pas di jari manis saya.', 'days_ago' => 10],
                ],
            ],
            [
                'product_name' => 'Butterfly Beads Bracelet',
                'testimonials' => [
                    ['username' => 'trandinhtuan567',  'rating' => 5, 'message' => 'Gelangnya cantik banget! Manik-maniknya tidak mudah copot. Sudah saya pakai tiap hari dan masih oke.', 'days_ago' => 8],
                    ['username' => 'hoangthanhphat88', 'rating' => 5, 'message' => 'Charm kupu-kupunya lucu dan detail. Sangat sesuai ekspektasi. Recommended!', 'days_ago' => 22],
                ],
            ],
            [
                'product_name' => 'Metallic Star Keychain',
                'testimonials' => [
                    ['username' => 'nguyenanhdao77', 'rating' => 4, 'message' => 'Gantungan kuncinya kuat dan berat di tangan (pertanda kualitas). Desainnya futuristik dan keren.', 'days_ago' => 18],
                ],
            ],
            [
                'product_name' => 'Cyber Heart Keychain',
                'testimonials' => [
                    ['username' => 'hoangthanhphat88', 'rating' => 5, 'message' => 'Sudah beli ini sebagai hadiah dan penerima sangat suka. Packaging premium dan detail finishing bagus banget.', 'days_ago' => 14],
                ],
            ],
        ];

        foreach ($productTestimonials as $pt) {
            $product = $products->firstWhere('product_name', $pt['product_name']);
            if (!$product) continue;

            foreach ($pt['testimonials'] as $t) {
                $user = $users->firstWhere('name', $t['username']);
                if (!$user) $user = $users->first();

                Testimonial::updateOrCreate(
                    [
                        'user_id'              => $user->id,
                        'product_id'           => $product->id,
                        'is_store_testimonial' => false,
                        'message'              => $t['message'],
                    ],
                    [
                        'rating'     => $t['rating'],
                        'created_at' => Carbon::now()->subDays($t['days_ago']),
                        'updated_at' => Carbon::now()->subDays($t['days_ago']),
                    ]
                );
            }
        }
    }
}
