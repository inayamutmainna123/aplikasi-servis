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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->ulid( 'id') ->primary();
            $table->foreignUlid('costumer_id')->nullable();
            $table->foreignUlid('sparepart_id')->nullable();
            $table->foreignUlid('service_item_id')->nullable(); 
            $table->double('jumlah_sparepart');
            $table->double('jumlah_service');
            $table->double('total_harga');
            $table->double('total_bayar');
            $table->double('total_kembalian');
            $table->enum ('metode_pembayaran', ['cash'])-> default('cash');
            $table->enum(   'status', ['belum lunas',' lunas']) ->default('belum lunas');
            $table->datetime('tanggal_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
