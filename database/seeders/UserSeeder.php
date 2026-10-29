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
    }
}