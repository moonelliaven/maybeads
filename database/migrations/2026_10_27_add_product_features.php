<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Perbesar kolom image agar cukup untuk nama file panjang
            $table->string('image', 255)->nullable()->change();

            // Kolom baru
            $table->json('secondary_categories')->nullable()->after('category_id');
            $table->json('variants')->nullable()->after('description');
            $table->json('secondary_images')->nullable()->after('image');
            $table->boolean('status')->default(true)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image', 20)->nullable()->change();
            $table->dropColumn(['secondary_categories', 'variants', 'secondary_images', 'status']);
        });
    }
};
