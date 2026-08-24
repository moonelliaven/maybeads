<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->string('photo_profile', 50)->nullable();
            $table->string('phone_number', 50);
            $table->text('address')->nullable();
            $table->string('role', 20);
            $table->timestamp('created_at')
                  ->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};