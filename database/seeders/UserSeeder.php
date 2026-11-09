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
            ['name' => 'moonelliaven', 'email' => 'moonelliaven@gmail.com', 'role' => 'user'],
            ['name' => 'nguyenvinhhoa567', 'email' => 'nguyenvinhhoa@example.com', 'role' => 'user'],
            ['name' => 'trantrungkien', 'email' => 'trantrungkien@example.com', 'role' => 'user'],
            ['name' => 'trandinhtuan567', 'email' => 'trandinhtuan@example.com', 'role' => 'user'],
            ['name' => 'nguyenanhdao77', 'email' => 'nguyenanhdao@example.com', 'role' => 'user'],
            ['name' => 'hoangthanhphat88', 'email' => 'hoangthanhphat@example.com', 'role' => 'user'],
            ['name' => 'lehongson2023', 'email' => 'lehongson@example.com', 'role' => 'user'],
            ['name' => 'bachdangthang', 'email' => 'bachdangthang@example.com', 'role' => 'user'],
            ['name' => 'nguyenminhkhoa101', 'email' => 'nguyenminhkhoa@example.com', 'role' => 'user'],
            ['name' => 'phamquynh88', 'email' => 'phamquynh@example.com', 'role' => 'user'],
        ];

        foreach ($dummyUsers as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password123'),
                    'role' => $u['role'],
                ]
            );
        }
    }
}