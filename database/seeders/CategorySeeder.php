<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'category_name' => 'Jepit Rambut'],
            ['id' => 2, 'category_name' => 'Cincin'],
            ['id' => 3, 'category_name' => 'Gelang'],
            ['id' => 4, 'category_name' => 'Gantungan Kunci'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                ['category_name' => $category['category_name']]
            );
        }
    }
}
