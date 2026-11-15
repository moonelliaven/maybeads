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
        // 1. Metode Pembayaran
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('type', 30)->default('e-wallet'); // bank, auto-link, qris, e-wallet
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->integer('price')->default(0);
            $table->enum('status', ['active', 'non-active'])->default('non-active');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 2. Metode Pengiriman
        Schema::create('delivery_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('price')->default(0);
            $table->enum('status', ['active', 'non-active'])->default('non-active');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('delivery_method_id')->nullable()->constrained('delivery_methods')->nullOnDelete();
            $table->string('payment_method')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status', 30)->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('delivery_methods');
        Schema::dropIfExists('payment_methods');
    }
};
