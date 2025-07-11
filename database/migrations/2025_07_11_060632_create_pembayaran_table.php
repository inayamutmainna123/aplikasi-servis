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
            $table->ulid( 'id')->primary()->index();
            $table->foreignUlid('costumer_id') ->nullable()->index();
            $table->foreignUlid('sparepart_id')->nullable()->index();
            $table->foreignUlid('service_item_id')->nullable()->index();
            $table->double('jumlah_sparepart')->default(0);
            $table->double('jumlah_service')->default(0);
            $table->double('total_harga')->nullable();
            $table->double('total_bayar')->nullable();
            $table->double('total_kembali')->default(0);
            $table->enum('status', ['belum lunas','lunas'])->default('belum lunas');
            $table->enum('metode_pembayaran', ['cash'])->default('cash');
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
