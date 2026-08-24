<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();

            // join id
            $table->foreignId('user_id')
                ->constrained('users')>onDelete('zcascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade'); 
        
            $table->integer('quantity')->default(1);
            $table->timestamp('added_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};