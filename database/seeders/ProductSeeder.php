<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'category_name');

        $products = [
            [
                'category_name' => 'Tops',
                'product_name' => 'Cyber Star Rhinestone Baby Tee',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80',
                'description' => 'Soft cotton baby tee with rhinestone graphic finish.',
                'price' => 48.00,
                'stock' => 8,
            ],
            [
                'category_name' => 'Bottoms',
                'product_name' => 'Parachute Wide-Leg Cargo Pants',
                'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80',
                'description' => 'Relaxed utility cargo with exaggerated wide-leg silhouette.',
                'price' => 89.00,
                'stock' => 12,
            ],
            [
                'category_name' => 'Outerwear',
                'product_name' => 'Liquid Metal Zip-Up Track Jacket',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
                'description' => 'A sleek zip track jacket with metallic detailing.',
                'price' => 115.00,
                'stock' => 6,
            ],
            [
                'category_name' => 'Jewelry',
                'product_name' => 'Beaded Chunky Y2K Choker',
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=80',
                'description' => 'Chunky resin bead choker inspired by 2000s style culture.',
                'price' => 34.00,
                'stock' => 15,
            ],
            [
                'category_name' => 'Accessories',
                'product_name' => 'Chrome Vision Crossbody',
                'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=900&q=80',
                'description' => 'Glossy metallic bag with compact silhouette and utility strap.',
                'price' => 68.00,
                'stock' => 9,
            ],
            [
                'category_name' => 'Tops',
                'product_name' => 'Midnight Gloss Graphic Tee',
                'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
                'description' => 'Crisp heavyweight tee with bold graphic print.',
                'price' => 52.00,
                'stock' => 10,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['product_name' => $product['product_name']],
                [
                    'category_id' => $categories[$product['category_name']] ?? $categories['Tops'],
                    'image' => $product['image'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                ]
            );
        }
    }
}
