<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranItemTable extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_item', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('pembayaran_id')->nullable();
            $table->foreignUlid('service_item_id')->nullable();
            $table->foreignUlid('sparepart_id')->nullable();
            $table->double('jumlah_service')->default(1);
            $table->double('jumlah_sparepart')->default(1);
            $table->double('harga_service')->default(0);
            $table->double('harga_sparepart')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_item');
    }
}
