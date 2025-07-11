<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Pembayaran extends Model
{
    use HasUlids;

    protected $table = 'pembayaran';

    protected $fillable = [
        'costumer_id',
        'sparepart_id',
        'service_item_id',
        'jumlah_sparepart',
        'jumlah_service',
        'total_harga',
        'total_bayar',
        'total_kembali',
        'metode_pembayaran',
        'status',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class,'costumer_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

    public function service_item()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }

    public function items()
    {
        return $this->hasMany(PivotTable::class,'pembayaran_id');
    }

    
}