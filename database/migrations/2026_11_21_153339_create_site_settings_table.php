<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Maybeads');
            $table->string('site_tagline')->nullable()->default('Y2K Handcrafted Beads Accessories');
            $table->string('phone')->nullable()->default('081234567890');
            $table->string('whatsapp')->nullable()->default('6281234567890');
            $table->string('email')->nullable()->default('info@maybeads.com');
            $table->text('address')->nullable()->default('Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru, Kalimantan Selatan');
            $table->text('maps_embed_url')->nullable();
            $table->string('opening_hours')->nullable()->default('Senin - Sabtu: 09:00 - 21:00 WITA');
            $table->string('instagram')->nullable()->default('maybeads.id');
            $table->string('tiktok')->nullable()->default('@maybeads.official');
            $table->string('logo')->nullable();
            $table->text('bank_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
