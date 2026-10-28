<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'category_name' => 'Jepit Rambut', 'image' => 'prod-hairclip.jpg'],
            ['id' => 2, 'category_name' => 'Cincin', 'image' => 'prod-ring.jpg'],
            ['id' => 3, 'category_name' => 'Gelang', 'image' => 'prod-butterfly.jpg'],
            ['id' => 4, 'category_name' => 'Gantungan Kunci', 'image' => 'prod-keychain.jpg'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}