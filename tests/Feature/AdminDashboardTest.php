<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_default_admin_user_is_seeded(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::query()->where('username', 'hanzen')->first();

        $this->assertNotNull($user);
        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('hanzen123', $user->password));
    }
}
