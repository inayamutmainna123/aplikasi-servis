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
        Schema::create("pivot_table", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('costumer_id')->nullable();
            $table->foreignUlid('sparepart_id')->nullable();
            $table->foreignUlid('service_item_id')->nullable();
            $table->foreignUlid('service_detail_id')->nullable();
            $table->foreignUlid('pembayaran_id')->nullable();
            $table->double('harga_service')->nullable();
            $table->double('harga_sparepart')->nullable();
            $table->double('jumlah_sparepart')->nullable();
            $table->double('jumlah_service')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
