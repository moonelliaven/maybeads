<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'secondary_images')) {
                    $table->json('secondary_images')->nullable()->after('image');
                }
                if (!Schema::hasColumn('products', 'secondary_categories')) {
                    $table->json('secondary_categories')->nullable()->after('secondary_images');
                }
                if (!Schema::hasColumn('products', 'order_count')) {
                    $table->unsignedInteger('order_count')->default(0)->after('stock');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'secondary_images')) {
                    $table->dropColumn('secondary_images');
                }
                if (Schema::hasColumn('products', 'secondary_categories')) {
                    $table->dropColumn('secondary_categories');
                }
                if (Schema::hasColumn('products', 'order_count')) {
                    $table->dropColumn('order_count');
                }
            });
        }
    }
};
