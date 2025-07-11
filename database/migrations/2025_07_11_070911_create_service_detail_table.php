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
            $table->ulid( 'id') ->primary()->index();
            $table->foreignUlid('service_item_id')->index()->nullable();
            $table->foreignUlid('sparepart_id')->index()->nullable();
            $table->foreignUlid('costumer_id')->index()->nullable();
            $table->foreignUlid('merek_kendaraan_id')->index()->nullable();
            $table->string('tipe_kendaraan');
            $table->string('model_kendaraan');
            $table->string('plat_kendaraan');
            $table->text('catatan')->nullable();
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
