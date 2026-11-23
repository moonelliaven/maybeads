<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashier_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable()->default('Customer Offline');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('payment_method', 30)->default('cash'); // cash, qris, debit, transfer
            $table->decimal('cash_paid', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('payment_status', 20)->default('lunas'); // lunas, pending, dibatalkan
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('cashier_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_transaction_id')->constrained('cashier_transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_info')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_transaction_items');
        Schema::dropIfExists('cashier_transactions');
    }
};
