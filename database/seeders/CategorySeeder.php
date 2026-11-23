<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'category_name' => 'Jepit Rambut', 'image' => 'category/prod-hairclip.jpg'],
            ['id' => 2, 'category_name' => 'Cincin', 'image' => 'category/prod-ring.jpg'],
            ['id' => 3, 'category_name' => 'Gelang', 'image' => 'category/prod-butterfly.jpg'],
            ['id' => 4, 'category_name' => 'Gantungan Kunci', 'image' => 'category/prod-keychain.jpg'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                [
                    'category_name' => $category['category_name'],
                    'image'         => $category['image'],
                ]
            );
        }
    }
}
