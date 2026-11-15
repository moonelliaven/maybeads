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
        // 1. Ensure payment_methods table exists
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('type', 30)->default('e-wallet');
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->integer('price')->default(0);
                $table->enum('status', ['active', 'non-active'])->default('non-active');
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }

        // 2. Ensure delivery_methods table exists
        if (!Schema::hasTable('delivery_methods')) {
            Schema::create('delivery_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->integer('price')->default(0);
                $table->enum('status', ['active', 'non-active'])->default('non-active');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Ensure testimonials table has product_id and is_store_testimonial
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                if (!Schema::hasColumn('testimonials', 'product_id')) {
                    $table->foreignId('product_id')
                        ->nullable()
                        ->after('user_id')
                        ->constrained('products')
                        ->nullOnDelete();
                }
                if (!Schema::hasColumn('testimonials', 'is_store_testimonial')) {
                    $table->boolean('is_store_testimonial')->default(false)->after('rating');
                }
            });
        }

        // 4. Ensure payments table has updated columns
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'payment_method_id')) {
                    $table->foreignId('payment_method_id')->nullable()->after('order_id')->constrained('payment_methods')->nullOnDelete();
                }
                if (!Schema::hasColumn('payments', 'delivery_method_id')) {
                    $table->foreignId('delivery_method_id')->nullable()->after('payment_method_id')->constrained('delivery_methods')->nullOnDelete();
                }
                if (!Schema::hasColumn('payments', 'bukti_pembayaran')) {
                    $table->string('bukti_pembayaran')->nullable()->after('payment_status');
                }
                if (!Schema::hasColumn('payments', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                if (Schema::hasColumn('testimonials', 'product_id')) {
                    $table->dropForeign(['product_id']);
                    $table->dropColumn('product_id');
                }
                if (Schema::hasColumn('testimonials', 'is_store_testimonial')) {
                    $table->dropColumn('is_store_testimonial');
                }
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'payment_method_id')) {
                    $table->dropForeign(['payment_method_id']);
                    $table->dropColumn('payment_method_id');
                }
                if (Schema::hasColumn('payments', 'delivery_method_id')) {
                    $table->dropForeign(['delivery_method_id']);
                    $table->dropColumn('delivery_method_id');
                }
                if (Schema::hasColumn('payments', 'bukti_pembayaran')) {
                    $table->dropColumn('bukti_pembayaran');
                }
            });
        }

        Schema::dropIfExists('delivery_methods');
        Schema::dropIfExists('payment_methods');
    }
};

