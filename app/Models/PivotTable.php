<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PivotTable extends Model
{
    use HasUlids;
    protected $table = "pivot_table";
    protected $fillable = [
        "costumer_id",
        "sparepart_id",
        "service_item_id",
        "service_detail_id",
        "pembayaran_id",
        "harga_service",
        "harga_sparepart",
        "jumlah_sparepart",
        "jumlah_service",
    ];

    public function serviceDetail()
    {
        return $this->belongsTo(ServiceDetail::class);
    }

    // public function service_item()
    // {
    //     return $this->belongsTo(\App\Models\ServiceItem::class, 'service_item_id');
    // }


    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }
    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
    public function service_item()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }
}
