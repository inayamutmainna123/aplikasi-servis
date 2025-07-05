<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    use HasUlids;
    protected $table = "service_detail";

    protected $fillable = [
        'service_item_id',
        'sparepart_id',
        'costumer_id',
        'tipe_kendaraan',
        'merek_kendaraan',
        'model_kendaraan',
        'plat_kendaraan',
        'catatan',
        'status',
        'tanggal_service',
    ];

        
    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

    
    public function items()
    {
        return $this->hasMany(PivotTable::class);
    }


}

