<?php

namespace App\Models;

use App\enums\StatusServiceDetail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sparepart;
use App\Models\ServiceItem;




class ServiceDetail extends Model
{
    use HasUlids;
    protected $table = "service_detail";

    protected $fillable = [
        'service_item_id',
        'sparepart_id',
        'costumer_id',
        'merek_kendaraan_id',
        'tipe_kendaraan',
        'model_kendaraan',
        'plat_kendaraan',
        'catatan',
        'status',
        'tanggal_service',
    ];

    protected $casts = [
        'status' => StatusServiceDetail::class,
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

    public function service_item()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }

    public function costumer()
    {
        return $this->belongsTo(Costumer::class, 'costumer_id');
    }

    public function merek_kendaraan()
    {
        return $this->belongsTo(MerekKendaraan::class, 'merek_kendaraan_id');
    }


    public function items()
    {
        return $this->hasMany(\App\Models\PivotTable::class, 'service_detail_id');
    }
}
