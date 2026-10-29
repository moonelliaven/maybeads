<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('product_name', 100)->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->string('color', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};