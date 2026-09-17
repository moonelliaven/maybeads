<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'hanzen'],
            [
                'email' => 'hanzen@maybeads.com',
                'password' => Hash::make('hanzen123'),
                'photo_profile' => null,
                'phone_number' => '081234567890',
                'address' => 'Maybeads HQ',
                'role' => 'admin',
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
