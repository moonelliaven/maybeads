<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'hanzen@maybeads.com'],
            [
                'name' => 'Hanzen',
                'password' => Hash::make('hanzen123'),
                'role' => 'admin',
            ]
        );

        // Categories
        $categories = [
            ['id' => 1, 'category_name' => 'Jepit Rambut', 'image' => 'prod-hairclip.jpg'],
            ['id' => 2, 'category_name' => 'Cincin',        'image' => 'prod-ring.jpg'],
            ['id' => 3, 'category_name' => 'Gelang',        'image' => 'prod-butterfly.jpg'],
            ['id' => 4, 'category_name' => 'Gantungan Kunci','image' => 'prod-keychain.jpg'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['id' => $cat['id']], $cat);
        }

        // Products
        $products = [
            [
                'category_id'  => 1,
                'product_name' => 'Star Hairclip Y2K',
                'image'        => 'prod-hairclip.jpg',
                'description'  => 'Jepit rambut aksen bintang krom gaya Y2K unik dan trendi.',
                'price'        => '35.000',
                'stock'        => 20,
            ],
            [
                'category_id'  => 2,
                'product_name' => 'Cyber Cross Ring',
                'image'        => 'prod-ring.jpg',
                'description'  => 'Cincin siluet gotik chrome perak kontemporer presisi tinggi.',
                'price'        => '50.000',
                'stock'        => 15,
            ],
            [
                'category_id'  => 3,
                'product_name' => 'Butterfly Beads Bracelet',
                'image'        => 'prod-butterfly.jpg',
                'description'  => 'Gelang manik biru transparan dengan charm kupu-kupu aesthetic.',
                'price'        => '45.000',
                'stock'        => 25,
            ],
            [
                'category_id'  => 4,
                'product_name' => 'Metallic Star Keychain',
                'image'        => 'prod-keychain.jpg',
                'description'  => 'Gantungan kunci rantai logam perak bergaya cyber futuristik.',
                'price'        => '38.000',
                'stock'        => 30,
            ],
            [
                'category_id'  => 4,
                'product_name' => 'Cyber Heart Keychain',
                'image'        => 'keychain-01.jpg',
                'description'  => 'Gantungan kunci hati chrome dengan rantai kokoh edisi terbatas.',
                'price'        => '42.000',
                'stock'        => 18,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['product_name' => $prod['product_name']], $prod);
        }
    }
}
