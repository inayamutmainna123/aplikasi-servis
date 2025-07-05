<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PembayaranItem extends Model
{
    use HasUlids;
    protected $table = "pembayaran_item";

    protected $fillable = [
        'pembayaran_id',
        'service_item_id',
        'sparepart_id',
        'harga_service',
        'harga_sparepart',
        'jumlah_sparepart',
        'jumlah_service',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
    
}
