<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('product_name', 100);
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->string('order_status', 20)->default('pending');
            $table->string('payment_status', 20)->default('unpaid');
            $table->text('address');
            $table->string('phone_number')->nullable();
            $table->timestamp('order_at')->useCurrent();
            $table->timestamp('send_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};