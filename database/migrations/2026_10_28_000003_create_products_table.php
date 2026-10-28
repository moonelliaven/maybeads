<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->json('secondary_categories')->nullable();
            $table->string('product_name', 100)->unique();
            $table->string('image', 255)->nullable();
            $table->json('secondary_images')->nullable();
            $table->text('description')->nullable();
            $table->json('variants')->nullable();
            $table->string('price', 100);
            $table->integer('stock')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};