<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasUlids;
    protected $table = "pembayaran";

    protected $fillable = [
        'costumer_id',
        'sparepart_id',
        'service_item_id',
        'jumlah_sparepart',
        'jumlah_service',
        'total_harga',
        'total_bayar',
        'total_kembalian',
        'metode_pembayaran',
        'status',
        'tanggal_pembayaran',
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

        public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    
    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function items()
    {
        return $this->hasMany(PembayaranItem::class);
    }
}

