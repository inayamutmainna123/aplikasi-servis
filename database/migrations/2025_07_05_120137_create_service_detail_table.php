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
        Schema::create('service_detail', function (Blueprint $table) {
            $table->ulid( 'id') ->primary();
            $table->foreignUlid('service_item_id')->nullable();
            $table->foreignUlid('produk_id')->nullable();
            $table->string('tipe_kendaraan');
            $table->string('merek_kendaraan');
            $table->string('model_kendaraan');
            $table->string('plat_kendaraan');
            $table->text('catatan');
            $table->enum('status' , ['belum diperbaiki', 'sedang diperbaiki', 'selesai diperbaiki']) ->default('belum diperbaiki');
            $table->datetime('tanggal_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_detail');
    }
};
