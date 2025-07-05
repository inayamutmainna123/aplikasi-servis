<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class service_detail extends Model
{
    use HasUlids;
    protected $table = "service_detail";

    protected $fillable = [
        'service_item_id',
        'produk_id',
        'tipe_kendaraan',
        'merek_kendaraan',
        'model_kendaraan',
        'plat_kendaraan',
        'catatan',
        'status',
        'tanggal_service',
    ];
}
