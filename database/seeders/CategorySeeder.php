<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Tops', 'image' => 'tops'],
            ['category_name' => 'Bottoms', 'image' => 'bottoms'],
            ['category_name' => 'Outerwear', 'image' => 'outerwear'],
            ['category_name' => 'Accessories', 'image' => 'accessories'],
            ['category_name' => 'Jewelry', 'image' => 'jewelry'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['category_name' => $category['category_name']],
                ['image' => $category['image']]
            );
        }
    }
}
