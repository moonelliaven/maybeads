<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'hanzen@maybeads.com'],
            [
                'name' => 'Hanzen',
                'password' => Hash::make('hanzen123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'hlccntauni@gmail.com'],
            [
                'name' => 'Hilya',
                'password' => Hash::make('Hilya12345'),
                'role' => 'user',
            ]
        );

        $dummyUsers = [
            ['name' => 'trandinhtuan567', 'email' => 'trandinhtuan@example.com', 'role' => 'user', 'address' => 'Jl. 1 No. 5 Kampung Sigm..'],
            ['name' => 'nguyenanhdao77', 'email' => 'nguyenanhdao@example.com', 'role' => 'user', 'address' => 'Jl. 4 No. 2 Kampung Sigm..'],
            ['name' => 'nguyenminhkhoa101', 'email' => 'nguyenminhkhoa@example.com', 'role' => 'user', 'address' => 'Jl. 6 No. 2 Kampung Sigm..'],
            ['name' => 'phamquynh88', 'email' => 'phamquynh@example.com', 'role' => 'user', 'address' => 'Jl. 4 No. 2 Kampung Sigm..'],
            ['name' => 'lequocviet101', 'email' => 'lequocviet@example.com', 'role' => 'user', 'address' => 'Jl. 3 No. 2 Kampung Sigm..'],
            ['name' => 'dinhhoangyen88', 'email' => 'dinhhoangyen@example.com', 'role' => 'user', 'address' => 'Jl. 4 No. 2 Kampung Sigm..'],
            ['name' => 'lethihoa99', 'email' => 'lethihoa@example.com', 'role' => 'user', 'address' => 'Jl. 1 No. 2 Kampung Sigm..'],
            ['name' => 'nguyenthiphuong', 'email' => 'nguyenthiphuong@example.com', 'role' => 'user', 'address' => 'Jl. 1 No. 4 Kampung Sigm..'],
            ['name' => 'nguyentranhieu', 'email' => 'nguyentranhieu@example.com', 'role' => 'user', 'address' => 'Jl. 1 No. 3 Kampung Sigm..'],
        ];

        foreach ($dummyUsers as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password123'),
                    'role' => $u['role'],
                    'address' => $u['address'],
                ]
            );
        }
    }
}